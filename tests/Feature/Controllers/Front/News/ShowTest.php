<?php

namespace Tests\Feature\Controllers\Front\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    public function test_published_as_guest(): void
    {
        $post = NewsPost::factory()->published()->createOne();
        $response = $this->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertViewIs('front.news.show');
        $response->assertViewHas('post');
    }

    public function test_published_as_user(): void
    {
        $post = NewsPost::factory()->published()->createOne();
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertViewIs('front.news.show');
        $response->assertViewHas('post');
    }

    public function test_unpublished_as_guest(): void
    {
        $post = NewsPost::factory()->unpublished()->createOne();
        $response = $this->get(route('news.show', ['slug' => $post->slug]));

        $response->assertNotFound();
    }

    public function test_unpublished_as_user(): void
    {
        $post = NewsPost::factory()->unpublished()->createOne();
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => $post->slug]));

        $response->assertOk();
        $response->assertViewIs('front.news.show');
        $response->assertViewHas('post');
    }

    public function test_invalid_post(): void
    {
        $response = $this->actingAs($this->user)->get(route('news.show', ['slug' => '404']));

        $response->assertNotFound();
    }
}
