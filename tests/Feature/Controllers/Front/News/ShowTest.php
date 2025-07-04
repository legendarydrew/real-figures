<?php

namespace Test\Feature\Controllers\Front\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    public function test_published_as_guest()
    {
        $post     = NewsPost::factory()->published()->createOne();
        $response = $this->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/news-post')->has('post'));
    }

    public function test_published_as_user()
    {
        $post     = NewsPost::factory()->published()->createOne();
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/news-post')->has('post'));
    }

    public function test_unpublished_as_guest()
    {
        $post     = NewsPost::factory()->unpublished()->createOne();
        $response = $this->get(route('news.show', ['slug' => $post->slug]));

        $response->assertNotFound();
    }

    public function test_unpublished_as_user()
    {
        $post     = NewsPost::factory()->unpublished()->createOne();
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/news-post')->has('post'));
    }

    public function test_invalid_post()
    {
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => '404']));

        $response->assertNotFound();
    }

}
