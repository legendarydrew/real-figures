<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoteRequest;
use App\Models\Round;
use App\Models\RoundVote;
use Illuminate\Http\JsonResponse;

class VoteController extends Controller
{
    //

    public function store(VoteRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Perform additional checks!

        $round = Round::with('songs')->find($data['round_id']);
        if (now() < $round->starts_at || $round->ends_at < now())
        {
            abort(400, 'Invalid round.');
        }

        $song_votes     = [$data['first_choice_id'], $data['second_choice_id'], $data['third_choice_id']];
        $round_song_ids = $round->songs->pluck('id')->toArray();
        foreach ($song_votes as $song_vote)
        {
            if (!in_array($song_vote, $round_song_ids))
            {
                abort(400, 'An invalid song was chosen.');
            }
        }

        RoundVote::create([
            'round_id'         => $round->id,
            'first_choice_id'  => $data['first_choice_id'],
            'second_choice_id' => $data['second_choice_id'],
            'third_choice_id'  => $data['third_choice_id'],
        ]);

        return response()->json(null, 201);
    }
}
