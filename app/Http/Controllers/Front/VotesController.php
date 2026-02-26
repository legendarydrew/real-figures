<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Transformers\StageVoteBreakdownTransformer;
use Illuminate\View\View;

/**
 * VotesController
 * A page that displays a breakdown of votes for each Stage.
 * Only breakdowns for over Stages will be shown.
 * If there are no over Stages, this page should not be accessible.
 *
 * @package App\Http\Controllers\Front
 */
class VotesController extends Controller
{
    public function index(): View
    {
        $stages = Stage::whereHas('outcomes')
                       ->orderByDesc('id')
                       ->get()
                       ->filter(fn(Stage $stage) => $stage->isOver());
        if ($stages->isNotEmpty())
        {
            return view('front.votes', [
                'stages' => fractal($stages, StageVoteBreakdownTransformer::class)->toArray()
            ]);
        }

        abort(404);
    }

}
