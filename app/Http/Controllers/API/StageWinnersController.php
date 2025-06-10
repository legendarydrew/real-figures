<?php

namespace App\Http\Controllers\API;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\StageWinnerRequest;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StageWinnersController extends Controller
{
    /**
     * Create StageWinner entries for the winning and highest-scoring runner-up Songs
     * featured in the specified Stage.
     * If there are tied results, it is possible for there to be more than one "winner"
     * and more than the requested number of runners-up.
     *
     * @param StageWinnerRequest $request
     * @param int                $stage_id
     * @return RedirectResponse
     */
    public function store(StageWinnerRequest $request, int $stage_id): RedirectResponse
    {
        $runner_up_count = $request->get('runners_up', 0);
        $stage           = Stage::findOrFail($stage_id);

        [$winners, $runners_up] = ContestFacade::determineStageWinners($stage, $runner_up_count);


        DB::transaction(function () use ($stage, $winners, $runners_up)
        {
            foreach ($winners as $winner)
            {
                StageWinner::create([
                    'stage_id'  => $stage->id,
                    'round_id'  => $winner->round_id,
                    'song_id'   => $winner->song_id,
                    'is_winner' => true
                ]);
            }
            foreach ($runners_up as $runner_up)
            {
                StageWinner::create([
                    'stage_id'  => $stage->id,
                    'round_id'  => $runner_up->round_id,
                    'song_id'   => $runner_up->song_id,
                    'is_winner' => false
                ]);
            }
        });

        return to_route('admin.stages');
    }
}
