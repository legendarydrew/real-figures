<?php

namespace Tests\Feature\Controllers\Front\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_news(): void
    {
        NewsPost::truncate();
        $response = $this->get(route('news'));

        $response->assertNotFound();
    }

    public function test_no_published_news(): void
    {
        NewsPost::factory()->count(10)->unpublished()->create();
        $response = $this->get(route('news'));

        $response->assertNotFound();
    }

    public function test_published_news(): void
    {
        NewsPost::factory()->count(10)->published()->create();
        $response = $this->get(route('news'));

        $response->assertOk();
        $response->assertViewIs('front.news.index');
        $response->assertViewHas('posts');

        $response = $this->get(route('news'), ['page' => 2]);

        $response->assertOk();
        $response->assertViewIs('front.news.index');
        $response->assertViewHas('posts');
    }
}
