<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EditTest extends TestCase
{
    use DatabaseMigrations;

    private NewsPost $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = NewsPost::factory()->createOne();
    }

    public function test_as_guest()
    {
        $response = $this->get(route('admin.news.edit', ['id' => $this->post->id]));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.news.edit', ['id' => $this->post->id]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/news-edit')->where('post.id', $this->post->id));
    }

    public function test_invalid_post()
    {
        $response = $this->actingAs($this->user)->get(route('admin.news.edit', ['id' => 404]));

        $response->assertNotFound();
    }

}
