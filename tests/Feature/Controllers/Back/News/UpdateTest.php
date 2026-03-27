<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Facades\ContestFacade;
use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    private NewsPost $post;

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        ContestFacade::partialMock();

        $this->post = NewsPost::factory()->unpublished()->createOne();
        $this->payload = [
            'id' => $this->post->id,
            'title' => fake()->sentence(),
            'content' => fake()->paragraph(),
        ];
    }

    public function test_as_guest(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->never();
        $response = $this->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->never();
        $response = $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);
    }

    public function test_updates_post(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->never();
        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $this->post->refresh();
        self::assertEquals($this->payload['title'], $this->post->title);
        self::assertEquals($this->payload['content'], $this->post->content);
    }

    public function test_preserve_published_date(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->never();
        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);
        $this->post->refresh();
        self::assertNull($this->post->published_at);

        $date = now()->microseconds(0);
        $this->post->published_at = $date;
        $this->post->save();

        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);
        $this->post->refresh();
        self::assertEquals($date, $this->post->published_at);
    }

    public function test_publish_post(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->once();
        $this->payload['publish'] = true;
        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $this->post->refresh();
        self::assertNotNull($this->post->published_at);
    }

    public function test_unpublish_post(): void
    {
        ContestFacade::shouldReceive('pingNewsPost')->never();
        $this->post->published_at = now();
        $this->post->save();

        $this->payload['publish'] = false;
        $this->actingAs($this->user)->putJson(route('news.update', ['id' => $this->post->id]), $this->payload);

        $this->post->refresh();
        self::assertNull($this->post->published_at);
    }
}
