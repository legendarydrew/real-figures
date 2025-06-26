<?php

namespace App\Http\Controllers\Back;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

/**
 * NewsGenerateController
 * This endpoint is responsible for generating press releases using the OpenAI API.
 *
 * @package App\Http\Controllers\Back
 */
class NewsGenerateController extends Controller
{

    public function index()
    {
        // What kind of press releases do we want to generate:
        // - CONTEST - a general post about the contest.
        // - STAGE - specifically about a Stage and its Rounds.
        // - ROUND - specifically about a Round within a Stage.
        // - ACT - specifically about an Act.
        // - CUSTOM - at the user's discretion.

        return Inertia::render('back/news-generate', [
            'types' => NewsPostType::cases()
        ]);
    }
}
