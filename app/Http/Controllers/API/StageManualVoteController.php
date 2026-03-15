<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManualVoteRequest;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Transformers\RoundAdminTransformer;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * StageManualVoteController
 * This endpoint is for recording "manual votes" for Songs in a Round.
 * A "manual vote" takes place to determine a winner when and if a Round ends with no votes cast.
 *
 * @package App\Http\Controllers\API
 */
class StageManualVoteController extends Controller
{

    /**
     * Display the manual vote page, with only the Rounds in the specified Stage that require a manual vote.
     *
     * @param int $stage_id
     * @return \Inertia\Response|RedirectResponse
     */
    public function show(int $stage_id): \Inertia\Response|RedirectResponse
    {
        $stage  = Stage::findOrFail($stage_id);
        $rounds = $stage->rounds->filter(fn(Round $round) => $round->requiresManualVote());

        if ($rounds->isEmpty())
        {
            return to_route('admin.stages')->withErrors('Stage does not require manual votes.');
        }

        return Inertia::render('back/manual-vote-page', [
            'stage'  => [
                'id'    => $stage->id,
                'title' => $stage->title
            ],
            'rounds' => fn() => fractal($rounds)->parseIncludes(['songs'])
                                                ->transformWith(new RoundAdminTransformer())
                                                ->toArray(),
        ]);
    }

    /**
     * Cast the manual votes for the specified Stage.
     *
     * @param ManualVoteRequest $request
     * @param int               $stage_id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(ManualVoteRequest $request, int $stage_id): RedirectResponse
    {
        Stage::findOrFail($stage_id);

        $data = $request->validated();
        DB::transaction(function () use ($data, $stage_id)
        {
            foreach ($data['votes'] as $vote)
            {
                $round = Round::whereStageId($stage_id)->findOrFail($vote['round_id']);
                if ($round->requiresManualVote())
                {
                    $song_votes     = collect([
                        $vote['song_ids']['first'],
                        $vote['song_ids']['second'],
                        $vote['song_ids']['third']
                    ]);
                    $round_song_ids = $round->songs->pluck('id')->toArray();
                    $other_songs    = array_filter($round_song_ids, fn($id) => !$song_votes->contains($id));
                    if (!$song_votes->every(fn($song_vote) => in_array($song_vote, $round_song_ids)))
                    {
                        abort(400, "{$round->title}: An invalid Song was chosen.");
                    }

                    // Create a RoundOutcome for each Song.
                    // Start with the Songs voted for, then create "empty" outcomes for the others.
                    RoundOutcome::factory($song_votes->count())
                                ->manualVote()
                                ->for($round)
                                ->create([
                                    'song_id'      => new Sequence(...$song_votes),
                                    'first_votes'  => new Sequence(1, 0, 0),
                                    'second_votes' => new Sequence(0, 1, 0),
                                    'third_votes'  => new Sequence(0, 0, 1)
                                ]);
                    RoundOutcome::factory(count($other_songs))
                                ->manualVote()
                                ->for($round)
                                ->create([
                                    'song_id'      => new Sequence(...$other_songs),
                                    'first_votes'  => 0,
                                    'second_votes' => 0,
                                    'third_votes'  => 0
                                ]);
                }
            }
        });

        return to_route('admin.stages');
    }
}
