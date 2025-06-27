<?php

namespace Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\NewsPost;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CustomPromptTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'type'   => NewsPostType::CUSTOM_POST_TYPE->value,
            'prompt' => fake()->sentence()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_custom_prompt()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_custom_prompt_with_previous_post()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $post->title));
        self::assertTrue(str_contains($prompt, $post->content));

    }

    public function test_custom_prompt_contains_prompt()
    {
        $this->payload['prompt'] = fake()->paragraph();
        $response                = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $this->payload['prompt']));
    }

    public function test_all_placeholders_filled()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        preg_match_all('(\:\w+)', $prompt, $matches);
        self::assertCount(0, $matches[0]);
    }

    public function test_invalid_previous_post()
    {
        $this->payload['previous'] = [404];
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();
    }
}
