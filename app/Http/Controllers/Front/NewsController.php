<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Transformers\NewsPostTransformer;
use Illuminate\Http\Response;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        if (NewsPost::published()->count() === 0) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $posts = NewsPost::published()
            ->orderByDesc('published_at')
            ->paginate(6);

        return view('front.news.index', [
            'posts' => fractal($posts)
                ->transformWith(NewsPostTransformer::class)
                ->withResourceName('data')
                ->toArray(),
            'paginator' => $posts,
        ]);
    }

    public function show(string $slug): View
    {
        $post = NewsPost::whereSlug($slug)->firstOrFail();

        // If the News Post has not been published, only allow viewing it if we are logged in.
        if (! $post->published_at && ! auth()->check()) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return view('front.news.show', [
            'post' => fractal($post)->transformWith(new NewsPostTransformer)
                ->parseIncludes(['content', 'acts', 'pages'])
                ->toArray(),
        ]);
    }
}
