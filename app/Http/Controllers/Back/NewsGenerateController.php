<?php

namespace App\Http\Controllers\Back;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Round;
use App\Models\Stage;
use Inertia\Inertia;
use Inertia\Response;

/**
 * NewsGenerateController
 * This endpoint is responsible for generating press releases using the OpenAI API.
 *
 * @package App\Http\Controllers\Back
 */
class NewsGenerateController extends Controller
{

    public function index(): Response
    {
        /**
         * What kind of press releases do we want to generate:
         *
         * - CONTEST - a general post about the contest.
         * - STAGE - specifically about a Stage and its Rounds.
         *  - provide a list of active, in progress and ended Stages.
         * - ROUND - specifically about a Round within a Stage.
         * - ACT - specifically about an Act.
         * - CUSTOM - at the user's discretion.
         *
         * We want to ensure that we only fetch information we need.
         */
        return Inertia::render('back/news-generate', [
            'types'  => NewsPostType::cases(),
            'stages' => Inertia::optional(fn() => Stage::all()
                                                       ->filter(fn(Stage $stage) => !$stage->isInactive())
                                                       ->map(fn(Stage $stage) => [
                                                           'id'     => $stage->id,
                                                           'title'  => $stage->title,
                                                           'status' => $stage->status
                                                       ])),
            'rounds' => Inertia::optional(fn() => Round::started()->whereHas('songs')->get()
                                                       ->map(fn(Round $round) => [
                                                           'id'    => $round->id,
                                                           'title' => $round->full_title
                                                       ])),
            'acts'   => Inertia::optional(fn() => Act::whereHas('songs')->orderBy('name')->get()
                                                     ->map(fn(Act $act) => [
                                                         'id'   => $act->id,
                                                         'name' => $act->name
                                                     ]))
        ]);
    }
}
