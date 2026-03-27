<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Round;
use App\Models\RoundVote;
use Illuminate\Http\JsonResponse;

/**
 * VoteController
 * Cast a vote for Songs in their respective Round.
 * Originally Visitors would be asked for their top three Songs, but ChatGPT suggested
 * a lower-friction option of voting for up to three Songs. The scoring will remain
 * the same.
 */
class VoteController extends Controller
{
    //

    public function store(VoteRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Perform additional checks!

        $round = Round::with('songs')->findOrFail($data['round_id']);
        if (!$round->hasStarted() || $round->hasEnded())
        {
            abort(400, 'Invalid round.');
        }

        $song_votes     = collect([$data['first_choice_id'], $data['second_choice_id'], $data['third_choice_id']])
            ->filter(fn($choice) => !is_null($choice));
        $round_song_ids = $round->songs->pluck('id')->toArray();
        if (!$song_votes->every(fn($song_vote) => in_array($song_vote, $round_song_ids)))
        {
            abort(400, 'An invalid Song was chosen.');
        }

        $choices = $song_votes->toArray();

        RoundVote::create([
            'round_id'         => $round->id,
            'first_choice_id'  => $choices[0],
            'second_choice_id' => $choices[1] ?? null,
            'third_choice_id'  => $choices[2] ?? null,
        ]);

        return response()->json(null, 201);
    }
}
