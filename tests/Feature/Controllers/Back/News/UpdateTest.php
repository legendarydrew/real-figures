<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    private NewsPost $post;
    private array    $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post    = NewsPost::factory()->createOne();
        $this->payload = [
            'id'      => $this->post->id,
            'title'   => fake()->sentence(),
            'content' => fake()->paragraph()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);
    }

    public function test_updates_post()
    {
        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $this->post->refresh();
        self::assertEquals($this->payload['title'], $this->post->title);
        self::assertEquals($this->payload['content'], $this->post->content);
    }

}
