<?php

namespace App\Http\Controllers\Front;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $sitemap = Sitemap::create();

        // Home page.
        $sitemap->add(Url::create(route('home'))->setPriority(1));

        // News pages (if there are published News Posts to show).
        if (ContestFacade::shouldShowNews())
        {
            $sitemap->add(Url::create(route('news')));

            NewsPost::published()->get()
                    ->each(function (NewsPost $post) use (&$sitemap)
                    {
                        $sitemap->add(Url::create(route('news.show', ['slug' => $post->slug]))->setLastModificationDate($post->updated_at));
                    });
        }

        // Acts page (if there are Acts to show).
        if (ContestFacade::shouldShowActs())
        {
            $sitemap->add(Url::create(route('acts')));

            Act::whereHas('songs')->whereHas('profile')->get()->each(function (Act $act) use (&$sitemap)
            {
                $sitemap->add(Url::create(route('act', ['slug' => $act->slug])));
            });
        }

        // Rules page.
        $sitemap->add(Url::create(route('rules')));

        // Donations page.
        $sitemap->add(Url::create(route('donations')));

        // Votes page (if available).
        if (ContestFacade::isOver())
        {
            $sitemap->add(Url::create(route('votes')));
        }

        // About page.
        $sitemap->add(Url::create(route('about')));

        // Contact page.
        $sitemap->add(Url::create(route('contact')));

        return $sitemap->toResponse($request);
    }
}
