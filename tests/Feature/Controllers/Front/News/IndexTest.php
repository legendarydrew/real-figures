<?php

namespace Tests\Feature\Controllers\Front\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_news()
    {
        NewsPost::truncate();
        $response = $this->get(route('news'));

        $response->assertNotFound();
    }

    public function test_no_published_news()
    {
        NewsPost::factory()->count(10)->unpublished()->create();
        $response = $this->get(route('news'));

        $response->assertNotFound();
    }

    public function test_published_news()
    {
        NewsPost::factory()->count(10)->published()->create();
        $response = $this->get(route('news'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/news')->has('posts'));

        $response = $this->get(route('news'), ['page' => 2]);

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/news')->has('posts'));
    }

}
