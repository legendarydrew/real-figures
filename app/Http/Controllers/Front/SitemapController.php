<?php

namespace App\Http\Controllers\Front;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

/**
 * SitemapController
 * Responsible for the creation of an XML sitemap file.
 */
class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $sitemap = Sitemap::create();

        // Home page.
        $sitemap->add(Url::create(route('home'))->setPriority(1));

        // Contest page.
        $sitemap->add(Url::create(route('contest'))->setPriority(0.9));

        // News pages (if there are published News Posts to show).
        if (ContestFacade::shouldShowNews()) {
            $sitemap->add(Url::create(route('news')));

            NewsPost::published()->get()
                ->each(function (NewsPost $post) use (&$sitemap) {
                    $sitemap->add(Url::create(route('news.show', ['slug' => $post->slug]))->setLastModificationDate($post->updated_at));
                });
        }

        // Acts page (if there are Acts to show).
        if (ContestFacade::shouldShowActs()) {
            $sitemap->add(Url::create(route('acts')));
        }

        // Rules page.
        $sitemap->add(Url::create(route('rules')));

        // Donations page.
        $sitemap->add(Url::create(route('donate')));

        // Votes page (if available).
        if (ContestFacade::isOver()) {
            $sitemap->add(Url::create(route('votes')));
        }

        // About page.
        $sitemap->add(Url::create(route('about')));

        // Contact page.
        $sitemap->add(Url::create(route('contact')));

        return $sitemap->toResponse($request);
    }
}
