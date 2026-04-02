<?php

namespace App\Http\Controllers\API;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManualVoteRequest;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Stage;
use App\Transformers\RoundAdminTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * StageManualVoteController
 * This endpoint is for recording "manual votes" for Songs in a Round.
 * A "manual vote" takes place to determine a winner when and if a Round ends with no votes cast.
 */
class StageManualVoteController extends Controller
{
    /**
     * Display the manual vote page, with only the Rounds in the specified Stage that require a manual vote.
     */
    public function show(int $stage_id): \Inertia\Response|RedirectResponse
    {
        $stage = Stage::findOrFail($stage_id);
        $rounds = $stage->rounds->filter(fn (Round $round) => $round->requiresManualVote());

        if ($rounds->isEmpty()) {
            return to_route('admin.stages')->withErrors('Stage does not require manual votes.');
        }

        return Inertia::render('back/manual-vote-page', [
            'stage' => [
                'id' => $stage->id,
                'title' => $stage->title,
            ],
            'rounds' => fn () => fractal($rounds)->parseIncludes(['songs'])
                ->transformWith(new RoundAdminTransformer)
                ->toArray(),
        ]);
    }

    /**
     * Cast the manual votes for the specified Stage.
     *
     * @throws \Throwable
     */
    public function store(ManualVoteRequest $request, int $stage_id): RedirectResponse
    {
        /* To make things more interesting: we have the option of simulating an "independent panel"
         * by setting CONTEST_PANEL_COUNT in the env file. This will cast random votes per member
         * in addition to the ones provided.
         * There is also a CONTEST_PANEL_BIAS setting, controlling how closely each panel member's
         * vote resembles the provided vote - which could mean that the Song we chose might not be
         * the winner!
         */
        Stage::findOrFail($stage_id);

        $data = $request->validated();
        DB::transaction(function () use ($data, $stage_id) {
            foreach ($data['votes'] as $vote) {
                $round = Round::whereStageId($stage_id)->findOrFail($vote['round_id']);
                if ($round->requiresManualVote()) {
                    // Check that all the Songs being voted for are part of the Round.
                    $round_song_ids = $round->songs->pluck('id')->toArray();
                    $voted_song_ids = collect(array_values($vote['song_ids']));
                    if (! $voted_song_ids->every(fn ($song_id) => in_array($song_id, $round_song_ids))) {
                        abort(Response::HTTP_BAD_REQUEST, "{$round->title}: An invalid Song was chosen.");
                    }

                    // Cast a vote for the Round, as directed.
                    RoundVote::create([
                        'round_id' => $round->id,
                        'first_choice_id' => $vote['song_ids']['first'],
                        'second_choice_id' => $vote['song_ids']['second'],
                        'third_choice_id' => $vote['song_ids']['third'],
                    ]);

                    // Cast panel member votes (if necessary).
                    $this->castPanelVotes($round->id, $round_song_ids, $vote['song_ids']);
                }

                // Recalculate outcomes for the Round.
                ContestFacade::buildRoundOutcomes($round, true);
            }
        });

        return to_route('admin.stages');
    }

    /**
     * Create "independent panel" votes for the specified Round.
     * These votes are determined at random, but might be biased toward the user's choices.
     *
     * @param  int  $round_id  the Round ID to vote on.
     * @param  array  $song_ids  a list of Song IDs.
     * @param  array  $voted  how the user voted for this Round.
     */
    protected function castPanelVotes(int $round_id, array $song_ids, array $voted): void
    {
        $vote_count = max(0, config('contest.judgement.panel-count'));
        $vote_bias = max(0, min(100, config('contest.judgement.panel-bias')));

        for ($i = 0; $i < $vote_count; $i++) {
            // Determine whether the panel member's vote matches the user's, or is completely random.
            $choices = fake()->boolean($vote_bias) ? array_values($voted) : fake()->randomElements($song_ids, 3);

            // Cast the vote!
            RoundVote::create([
                'round_id' => $round_id,
                'first_choice_id' => $choices[0],
                'second_choice_id' => $choices[1],
                'third_choice_id' => $choices[2],
            ]);

        }
    }
}
