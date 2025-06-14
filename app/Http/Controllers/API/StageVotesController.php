<?php

namespace App\Http\Controllers\API;

use App\Facades\VoteBreakdownFacade;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;

/**
 * StageVotesController
 * This endpoint provides a breakdown of votes for each Round.
 * Results will only be provided when the Stage has ended.
 *
 * @package App\Http\Controllers\API
 */
class StageVotesController extends Controller
{

    public function show(int $stage_id): JsonResponse
    {
        $stage = Stage::findOrFail($stage_id);
        if ($stage->hasEnded())
        {
            return response()->json(
                $stage->rounds->map(fn($round) => VoteBreakdownFacade::forRound($round))
            );
        }

        abort(412, 'Stage has not yet ended.');
    }
}
