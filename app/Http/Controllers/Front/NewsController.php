<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Transformers\NewsPostTransformer;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{

    public function show(string $slug): Response
    {
        $post = NewsPost::whereSlug($slug)->firstOrFail();

        // If the News Post has not been published, only allow viewing it if we are logged in.
        if (!$post->published_at && !auth()->check())
        {
            abort(404);
        }

        return Inertia::render('front/news-post', [
            'post' => fractal($post)->transformWith(new NewsPostTransformer())->parseIncludes(['content'])->toArray()
        ]);
    }
}
