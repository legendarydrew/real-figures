<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Transformers\NewsPostTransformer;
use Inertia\Inertia;
use Inertia\Response;

/**
 * NewsController
 * This set of endpoints are used to manage "press releases" for the contest.
 * The primary way of creating these press releases is through the OpenAI API.
 * Yes, we're going to use AI.
 *
 * @package App\Http\Controllers\Back
 */
class NewsController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('back/news', [
            'posts' => fn() => fractal(NewsPost::orderByDesc('id')->paginate())
                ->transformWith(NewsPostTransformer::class)
                ->withResourceName('data')
                ->toArray()
        ]);
    }
}
