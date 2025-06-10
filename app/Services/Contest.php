<?php

namespace App\Services;

use App\Facades\RoundResultsFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Contest
{

    /**
     * Returns TRUE if the contest is over.
     * The contest is considered over if all Stages are over.
     *
     * @return bool
     */
    public function isOver(): bool
    {
        $stages = Stage::all();
        return $stages->isNotEmpty() && $stages->every(fn(Stage $stage) => $stage->isOver());
    }

    /**
     * Returns the currently active Stage, if one is available.
     *
     * @return Stage|null
     */
    public function getCurrentStage(): Stage|null
    {
        $stages         = Stage::all();
        $previous_stage = null;
        foreach ($stages as $stage)
        {
            if ($stage->isInactive())
            {
                // The Stage has no Rounds - go no further.
                return $previous_stage;
            }
            elseif ($stage->isOver())
            {
                // The current Stage will be the last "over" Stage.
                // This would occur if we want to display the winners of the last Stage.
                $previous_stage = $stage;
            }
            else
            {
                // Any of the other states.
                return $stage;
            }
        }

        return $previous_stage;
    }

    /**
     * Returns TRUE if the current Stage is also the last Stage.
     * Used for identifying the final.
     *
     * @return bool
     */
    public function isOnLastStage(): bool
    {
        $current_stage = $this->getCurrentStage();
        $last_stage    = Stage::orderByDesc('id')->first();

        if ($current_stage && $last_stage)
        {
            return $current_stage->id === $last_stage->id;
        }

        return false;
    }

    /**
     * Create RoundOutcomes for the specified Round.
     *
     * @param Round $round
     * @return void
     * @throws \Throwable
     */
    public function buildRoundOutcome(Round $round): void
    {
        // Check that the Round has Votes for the round. If it has, create RoundOutcomes.
        // Bear in mind that it's possible for a Song not to have received any votes!
        if ($round->votes()->count())
        {
            $round->load(['votes', 'songs']);
            DB::transaction(function () use ($round)
            {
                $round->outcomes()->delete();

                $votes          = $round->votes;
                $first_choices  = array_count_values($votes->pluck('first_choice_id')->toArray());
                $second_choices = array_count_values($votes->pluck('second_choice_id')->toArray());
                $third_choices  = array_count_values($votes->pluck('third_choice_id')->toArray());

                foreach ($round->songs as $song)
                {
                    RoundOutcome::factory()
                                ->for($round)
                                ->for($song)
                                ->create([
                                    'first_votes'  => $first_choices[$song->id] ?? 0,
                                    'second_votes' => $second_choices[$song->id] ?? 0,
                                    'third_votes'  => $third_choices[$song->id] ?? 0,
                                ]);
                }
            });
        }

    }

    /**
     * Determine and return the winning Song(s) in the specified Stage.
     *
     * @param Stage    $stage
     * @param int|null $runner_up_count the number of runner-up Songs to include.
     * @return array a two-dimensional array including the winning and runner-up Songs.
     */
    public function determineStageWinners(Stage $stage, ?int $runner_up_count = null): array
    {
        if (!($stage->hasEnded() && $stage->outcomes()))
        {
            abort(400, 'The Stage has not ended.');
        }

        // The RoundResults service will return the rankings for individual Rounds.
        // We also want to obtain the scores for each runner-up, to determine which Songs
        // are the highest scoring.

        $winners    = new Collection();
        $runners_up = new Collection();

        foreach ($stage->rounds as $round)
        {
            $results = RoundResultsFacade::calculate($round, $runner_up_count);
            if ($results)
            {
                $winners    = $winners->merge($results['winners']);
                $runners_up = $runners_up->merge($results['runners_up']);
            }
        }

        // Find out which Songs were the highest-scoring runners-up.
        $runners_up = $runners_up->sortByDesc(fn(RoundOutcome $outcome) => $outcome->score)
                                 ->unique('song_id')
                                 ->slice(0, $runner_up_count);

        return [$winners, $runners_up];
    }

}
