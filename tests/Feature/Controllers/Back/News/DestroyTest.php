<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DestroyTest extends TestCase
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
        $response = $this->delete(route('news.destroy', ['id' => $this->post->id]));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->delete(route('news.destroy', ['id' => $this->post->id]));

        $response->assertRedirectToRoute('admin.news');
    }

    public function test_invalid_post()
    {
        $response = $this->actingAs($this->user)->delete(route('news.destroy', ['id' => 404]));

        $response->assertNotFound();
    }

}
