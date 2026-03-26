<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPostRequest;
use App\Models\NewsPost;
use App\Transformers\NewsPostTransformer;
use Garf\LaravelPinger\PingerFacade;
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
        return Inertia::render('back/news-page', [
            'posts' => fn() => fractal(NewsPost::orderByDesc('id')->paginate())
                ->transformWith(NewsPostTransformer::class)
                ->withResourceName('data')
                ->toArray()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('back/news-edit-page');
    }

    public function edit(int $id): Response
    {
        return Inertia::render('back/news-edit-page', [
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
        $data          = $request->validated();
        $post          = NewsPost::findOrFail($id);
        $was_published = false;

        if (isset($data['publish']))
        {
            if ($data['publish'] && is_null($post->published_at))
            {
                // Mark the News Post as published.
                $data['published_at'] = now();
                $was_published        = true;
            }
            else
            {
                // Give ourselves the ability to unpublish, if necessary in the future.
                $data['published_at'] = null;
            }
        }
        $post->update($data);

        // If the News Post was [newly] published, ping some search engines.
        // We only want to do this if the site is live.
        if ($was_published && app()->isProduction())
        {
            PingerFacade::pingAll($post->title, $post->url);
            // TODO add an RSS feed URL as the third parameter.
        }

        return to_route('admin.news.edit', ['id' => $post->id]);
    }

    public function destroy(int $id): RedirectResponse
    {
        $post = NewsPost::findOrFail($id);
        $post->deleteOrFail();

        return to_route('admin.news');

    }
}
