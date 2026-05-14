<?php

namespace App\Http\Controllers\API;

use App\Enums\VoteType;
use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\DumbrickRequest;
use App\Models\RoundVote;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * DumbrickController
 * This endpoint will cast votes for the current Round, based on values obtained from the Dumbrick project.
 * If there is no currently active Round, it will return a 404 error.
 *
 * @package App\Http\Controllers\API
 */
class DumbrickController extends Controller
{
    //

    /**
     * @throws FileNotFoundException
     */
    public function store(DumbrickRequest $request): JsonResponse
    {
        $current_round = ContestFacade::getCurrentStage()?->getCurrentRound();
        if (!$current_round)
        {
            abort(Response::HTTP_BAD_REQUEST, 'No current Round.');
        }

        // Read the contents of the uploaded file.
        // The data should be provided as groups of uppercase letters separated by a line break.
        // Each letter corresponds to a Song in the current Round.
        $data = $request->file('data')->get();
        $rows = explode(PHP_EOL, $data);

        // Cast each vote!
        $vote_count = 0;
        DB::transaction(function () use ($current_round, $rows, &$vote_count)
        {
            $song_ids = $current_round->songs()->pluck('id')->toArray();
            foreach ($rows as $row)
            {
                $choices = array_map(fn($letter) => ord($letter) - ord("A"), (array)$row);
                if (empty($choices))
                {
                    continue;
                }

                RoundVote::create([
                    'round_id'         => $current_round->id,
                    'vote_type'        => VoteType::DUMBRICK,
                    'first_choice_id'  => $song_ids[$choices[0]],
                    'second_choice_id' => $song_ids[$choices[1]] ?? null,
                    'third_choice_id'  => $song_ids[$choices[2]] ?? null,
                ]);
                $vote_count++;
            }
        });

        return response()->json(['votes' => $vote_count], Response::HTTP_CREATED);
    }
}
