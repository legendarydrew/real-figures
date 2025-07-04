<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPostRequest;
use App\Models\NewsPost;
use App\Transformers\NewsPostTransformer;
use Illuminate\Http\RedirectResponse;
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

    public function create(): Response
    {
        return Inertia::render('back/news-edit');
    }

    public function edit(int $id): Response
    {
        return Inertia::render('back/news-edit', [
            'post' => fn() => fractal(NewsPost::findOrFail($id))
                ->transformWith(NewsPostTransformer::class)
                ->toArray()
        ]);
    }

    public function store(NewsPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = NewsPost::factory()->unpublished()->create($data);

        return to_route('admin.news.edit', ['id' => $post->id]);
    }

    public function update(int $id, NewsPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $post = NewsPost::findOrFail($id);

        if (isset($data['publish']))
        {
            if ($data['publish'] && is_null($post->published_at))
            {
                // Mark the News Post as published.
                $data['published_at'] = now();
            }
            else
            {
                // Give ourselves the ability to unpublish, if necessary in the future.
                $data['published_at'] = null;
            }
        }
        $post->update($data);

        return to_route('admin.news.edit', ['id' => $post->id]);
    }
}
