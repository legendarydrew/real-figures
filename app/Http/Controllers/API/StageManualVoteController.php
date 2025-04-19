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
 * This endpoint records "manual votes" for Songs in a Round.
 * A "manual vote" takes place to determine a winner when and if a Round ends with no votes cast.
 *
 * @package App\Http\Controllers\API
 */
class StageManualVoteController extends Controller
{

    public function show(int $stage_id): \Inertia\Response|RedirectResponse
    {
        $stage  = Stage::findOrFail($stage_id);
        $rounds = $stage->rounds->filter(fn(Round $round) => $round->requiresManualVote());

        if ($rounds->isEmpty())
        {
            return to_route('admin.stages')->withErrors('Stage does not require manual votes.');
        }

        return Inertia::render('back/manual-vote', [
            'stageTitle' => $stage->title,
            'rounds'     => fn() => fractal($rounds)->parseIncludes(['songs'])
                                                    ->transformWith(new RoundAdminTransformer())
                                                    ->toArray(),
        ]);
    }

    public function store(ManualVoteRequest $request, int $stage_id): RedirectResponse
    {
        Stage::findOrFail($stage_id);

        $data = $request->validated();
        DB::transaction(function () use ($data, $stage_id)
        {
            foreach ($data['votes'] as $vote)
            {
                $round = Round::findOrFail($vote['round_id']);
                if (!($round->stage_id === $stage_id && $round->requiresManualVote()))
                {
                    continue;
                }

                $song_votes     = collect([$vote['song_ids']['first'], $vote['song_ids']['second'], $vote['song_ids']['third']]);
                $round_song_ids = $round->songs->pluck('id')->toArray();
                $other_songs    = array_filter($round_song_ids, fn($id) => !$song_votes->contains($id));
                if (!$song_votes->every(fn($song_vote) => in_array($song_vote, $round_song_ids)))
                {
                    abort(400, "{$round->title}: An invalid song was chosen.");
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
        });

        return to_route('admin.stages');
    }
}
