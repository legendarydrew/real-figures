<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SitemapTest extends TestCase
{

    use DatabaseMigrations;

    public function test_access()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertOk();
    }

    public function test_with_acts()
    {
        $acts     = Act::factory(4)->withProfile()->withSong()->create();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        foreach ($acts as $act)
        {
            $response->assertSee(route('act', ['slug' => $act->slug]));
        }
    }

    public function test_with_news()
    {
        $published_post   = NewsPost::factory(4)->published()->create();
        $unpublished_post = NewsPost::factory(4)->unpublished()->create();
        $response         = $this->get('/sitemap.xml');
        $response->assertOk();

        foreach ($published_post as $post)
        {
            $response->assertSee($post->url);
        }

        foreach ($unpublished_post as $post)
        {
            $response->assertDontSee($post->url);
        }
    }

    public function test_with_contest_over()
    {
        Stage::factory()->over()->create();
        $response = $this->get('/sitemap.xml');
        $response->assertOk();

        $response->assertSee(route('votes'));
    }

}
