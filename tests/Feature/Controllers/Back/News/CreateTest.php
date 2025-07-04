<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseMigrations;

    protected array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        fake()->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider(fake()));
        $this->payload = [
            'title'   => fake()->sentence,
            'content' => fake()->markdown()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->post(route('news.store'), $this->payload);

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->post(route('news.store'), $this->payload);

        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);
    }

    #[Depends('test_as_user')]
    public function test_creates_post()
    {
        $this->actingAs($this->user)->post(route('news.store'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertEquals($this->payload['title'], $post->title);
        self::assertEquals($this->payload['content'], $post->content);
        self::assertNull($post->published_at);
    }
}
