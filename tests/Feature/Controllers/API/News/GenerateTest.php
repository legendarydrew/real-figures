<?php

namespace Tests\Feature\Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenerateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/generate';
    private array $payload;

    public function test_as_guest()
    {
        $this->payload = [
            'type'   => NewsPostType::CUSTOM_POST_TYPE->value,
            'prompt' => fake()->sentence()
        ];

        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_contest_prompt()
    {
        $this->payload = [
            'type'   => NewsPostType::CONTEST_POST_TYPE->value,
            'prompt' => fake()->sentence()
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);

        $post = NewsPost::findOrFail(1);
        self::assertEquals(NewsPostType::CONTEST_POST_TYPE->value, $post->type);
        self::assertCount(0, $post->references);
    }

    public function test_stage_prompt()
    {
        $stage         = Stage::factory()->createOne();
        $this->payload = [
            'type'       => NewsPostType::STAGE_POST_TYPE->value,
            'prompt'     => fake()->sentence(),
            'references' => [$stage->id]
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);

        $post = NewsPost::findOrFail(1);
        self::assertEquals(NewsPostType::STAGE_POST_TYPE->value, $post->type);
        self::assertCount(1, $post->references);
    }

    public function test_round_prompt()
    {
        $stage         = Stage::factory()->createOne();
        $round         = Round::factory()->for($stage)->createOne();
        $this->payload = [
            'type'       => NewsPostType::ROUND_POST_TYPE->value,
            'prompt'     => fake()->sentence(),
            'references' => [$round->id]
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);

        $post = NewsPost::findOrFail(1);
        self::assertEquals(NewsPostType::ROUND_POST_TYPE->value, $post->type);
        self::assertCount(1, $post->references);
    }

    public function test_act_prompt()
    {
        $acts          = Act::factory(4)->create();
        $this->payload = [
            'type'       => NewsPostType::ACT_POST_TYPE->value,
            'prompt'     => fake()->sentence(),
            'references' => $acts->pluck('id')->toArray()
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);

        $post = NewsPost::findOrFail(1);
        self::assertEquals(NewsPostType::ACT_POST_TYPE->value, $post->type);
        self::assertCount(4, $post->references);
    }

    public function test_custom_with_prompt()
    {
        $this->payload = [
            'type'   => NewsPostType::CUSTOM_POST_TYPE->value,
            'prompt' => fake()->sentence()
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirectToRoute('admin.news.edit', ['id' => 1]);

        $post = NewsPost::findOrFail(1);
        self::assertEquals(NewsPostType::CUSTOM_POST_TYPE->value, $post->type);
        self::assertCount(0, $post->references);
    }

    public function test_custom_with_empty_prompt()
    {
        $this->payload = [
            'type'   => NewsPostType::CUSTOM_POST_TYPE->value,
            'prompt' => ''
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();

        $post = NewsPost::find(1);
        self::assertNull($post);
    }
}
