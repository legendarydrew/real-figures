<?php

namespace App\Http\Controllers\Front;

use App\Facades\VoteBreakdownFacade;
use App\Http\Controllers\Controller;
use App\Models\Round;
use App\Models\Stage;
use Inertia\Inertia;
use Inertia\Response;

/**
 * VotesController
 * A page that displays a breakdown of votes for each Stage.
 * Only breakdowns for over Stages will be shown. If there are no over Stages,
 * this page should not exist.
 *
 * @package App\Http\Controllers\Front
 */
class VotesController extends Controller
{
    public function index(): Response
    {
        $stages = Stage::orderByDesc('id')->get()->filter(fn(Stage $stage) => $stage->isOver());
        if ($stages->isNotEmpty())
        {
            return Inertia::render('front/votes', [
                'stages' => fn() => $stages->map(fn(Stage $stage) => [
                    'id'         => $stage->id, // for testing purposes.
                    'title'      => $stage->title,
                    'breakdowns' => $stage->rounds->map(fn(Round $round) => VoteBreakdownFacade::forRound($round))
                ]),
            ]);
        }

        abort(404);
    }

}
