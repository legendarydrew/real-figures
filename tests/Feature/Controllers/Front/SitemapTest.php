<?php

namespace Tests\Feature\Controllers\Front;

use App\Facades\ContestFacade;
use App\Models\Act;
use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class SitemapTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
    }

    public function test_without_acts(): void
    {
        Act::truncate();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertDontSee(route('acts'));
    }

    public function test_with_acts(): void
    {
        $acts = Act::factory(4)->withProfile()->withSong()->create();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertSee(route('acts'));
    }

    public function test_without_news(): void
    {
        NewsPost::truncate();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertDontSee(route('news'));
    }

    public function test_with_news(): void
    {
        $published_post = NewsPost::factory(4)->published()->create();
        $unpublished_post = NewsPost::factory(4)->unpublished()->create();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertSee(route('news'));

        foreach ($published_post as $post) {
            $response->assertSee($post->url);
        }

        foreach ($unpublished_post as $post) {
            $response->assertDontSee($post->url);
        }
    }

    public function test_with_contest_not_over(): void
    {
        ContestFacade::shouldReceive('isOver')->andReturn(false);
        ContestFacade::partialMock();

        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertDontSee(route('votes'));
    }

    public function test_with_contest_over(): void
    {
        ContestFacade::shouldReceive('isOver')->andReturn(true);
        ContestFacade::partialMock();

        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertSee(route('votes'));
    }
}
