<?php

namespace App\Services;

use App\Facades\ContestFacade;
use App\Facades\RoundResultsFacade;
use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Models\StageWinner;
use App\Transformers\SongTransformer;
use Garf\LaravelPinger\Pinger;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Contest
{
    /**
     * Returns TRUE if the Contest is over.
     * The Contest is considered over if all Stages are over.
     */
    public function isOver(): bool
    {
        $stages = Stage::all();

        return $stages->isNotEmpty() && $stages->every(fn (Stage $stage) => $stage->isOver());
    }

    /**
     * Returns TRUE if the Contest is currently underway.
     * The Contest is running if at least one Round has started.
     */
    public function isRunning(): bool
    {
        return Round::started()->count() > 0;
    }

    /**
     * Returns the currently active Stage, if one is available.
     */
    public function getCurrentStage(): ?Stage
    {
        $stages = Stage::all();
        $previous_stage = null;
        foreach ($stages as $stage) {
            if ($stage->isInactive()) {
                // The Stage has no Rounds - go no further.
                return $previous_stage;
            } elseif ($stage->isOver()) {
                // The current Stage will be the last "over" Stage.
                // This would occur if we want to display the winners of the last Stage.
                $previous_stage = $stage;
            } else {
                // Any of the other states.
                return $stage;
            }
        }

        return $previous_stage;
    }

    /**
     * Returns TRUE if the current Stage is also the last Stage.
     * Used for identifying the final.
     */
    public function isOnLastStage(): bool
    {
        $current_stage = $this->getCurrentStage();
        $last_stage = Stage::orderByDesc('id')->first();

        if ($current_stage && $last_stage) {
            return $current_stage->id === $last_stage->id;
        }

        return false;
    }

    /**
     * Create RoundOutcomes for the specified Round.
     *
     * @throws \Throwable
     */
    public function buildRoundOutcomes(Round $round, bool $manual = false): void
    {
        // Check that the Round has Votes for the round. If it has, create RoundOutcomes.
        // Bear in mind that it's possible for a Song not to have received any votes!
        if ($round->votes()->count()) {
            $round->load(['votes', 'songs']);
            DB::transaction(function () use ($round, $manual) {
                $round->outcomes()->delete();

                $votes          = $round->votes;
                $first_choices  = array_count_values($votes->pluck('first_choice_id')->filter()->toArray());
                $second_choices = array_count_values($votes->pluck('second_choice_id')->filter()->toArray());
                $third_choices  = array_count_values($votes->pluck('third_choice_id')->filter()->toArray());

                foreach ($round->songs as $song) {
                    RoundOutcome::factory()
                        ->for($round)
                        ->for($song)
                        ->create([
                            'first_votes' => $first_choices[$song->id] ?? 0,
                            'second_votes' => $second_choices[$song->id] ?? 0,
                            'third_votes' => $third_choices[$song->id] ?? 0,
                            'was_manual' => $manual,
                        ]);
                }
            });
        }

    }

    /**
     * Determine and return the winning Song(s) in the specified Stage.
     * If the Stage has not yet ended, null is returned.
     *
     * @param  int|null  $runner_up_count  the number of runner-up Songs to include.
     * @return array|null a two-dimensional array including the winning and runner-up Songs.
     */
    public function determineStageWinners(Stage $stage, ?int $runner_up_count = null): ?array
    {
        $runner_up_count = $runner_up_count ?? config('contest.judgement.runners-up');
        if ($stage->hasEnded() && $stage->outcomes()->count()) {
            // The RoundResults service will return the rankings for individual Rounds.
            // We also want to obtain the scores for each runner-up, to determine which Songs
            // are the highest scoring.

            $winners = new Collection;
            $runners_up = new Collection;

            foreach ($stage->rounds as $round) {
                $results = RoundResultsFacade::calculate($round, $runner_up_count);
                if ($results) {
                    $winners = $winners->merge($results['winners']);
                    $runners_up = $runners_up->merge($results['runners_up']);
                }
            }

            // Find out which Songs were the highest-scoring runners-up.
            $runners_up = $runners_up->sortByDesc(fn (RoundOutcome $outcome) => $outcome->score)
                ->unique('song_id')
                ->slice(0, $runner_up_count);

            return [$winners, $runners_up];
        }

        return null;
    }

    /**
     * Returns a breakdown of the winner(s), runners-up and other Songs in the Contest.
     * This corresponds to the outcome of the last Stage.
     * If the Contest is not over, null is returned.
     */
    public function overallWinners(): ?array
    {
        if (self::isOver()) {
            $last_stage = Round::orderByDesc('id')->first()->stage;
            // Determine the final Stage by the last Round.

            $all_winners = $last_stage->winners;

            $winners = $all_winners->filter(fn (StageWinner $winner) => $winner->is_winner);
            $runners_up = $all_winners->filter(fn (StageWinner $winner) => ! $winner->is_winner);

            return [
                'winners' => fractal($winners->map(fn (StageWinner $winner) => $winner->song), new SongTransformer)->toArray(),
                'runners_up' => fractal($runners_up->map(fn (StageWinner $winner) => $winner->song), new SongTransformer)->toArray(),
            ];
        }

        return null;
    }

    /**
     * Returns TRUE if the Acts page should be shown.
     * This should be the case if there is at least one Act with a Song.
     */
    public function shouldShowActs(): bool
    {
        return Act::whereHas('songs')->count() > 0;
    }

    /**
     * Returns TRUE if the News pages should be shown.
     * This should be the case if there is at least one published News Post.
     */
    public function shouldShowNews(): bool
    {
        return NewsPost::published()->count() > 0;
    }

    /**
     * Returns a list of date markers relating to the Contest.
     * These will be used to add indicators to charts in the back office.
     */
    public function getContestMarkers(): array
    {
        // We want to include:
        // - the start of each Stage (the creation date of the first Round in the Stage);
        // - the beginnings of each Round;
        // - the end of each Stage;
        // - the end of the Contest (winners determined).
        // If we want to be *extra*, we could try including dates of News and Subscriber posts,
        // which could be turned on/off in each chart.
        try {
            $stages = Stage::all()->filter(fn (Stage $stage) => $stage->rounds->count());
            $rounds = Round::all()->filter(fn (Round $round) => $round->hasStarted());
        } catch (\Exception $exception) {
            // There was an issue with deployment, where these results were attempted to be fetched
            // as part of the GitHub action.
            return [
                'stages' => [],
                'rounds' => [],
                'over' => null,
            ];
        }

        return [
            'stages' => $stages->map(fn (Stage $stage) => [
                'start' => $stage->rounds->first()->created_at->startOfDay()->toISOString(),
                'end' => $stage->rounds->last()->ends_at->startOfDay()->toISOString(),
                'name' => $stage->title,
            ]),
            'rounds' => $rounds->map((fn (Round $round) => [
                'date' => $round->starts_at->startOfDay()->toISOString(),
                'name' => $round->full_title,
            ])),
            'over' => ContestFacade::isOver() ?
                StageWinner::first()->created_at->startOfDay()->toISOString() : null,
        ];
    }

    /**
     * Ping search engines about the specified News post.
     * This was added to the Contest service to be able to test whether pinging was done.
     */
    public function pingNewsPost(NewsPost $post): void
    {
        if (app()->isProduction()) {
            (new Pinger)->pingAll($post->title, $post->url, route('feeds.main'));
        }

    }
}
