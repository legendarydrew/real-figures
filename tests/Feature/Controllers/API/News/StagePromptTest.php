<?php

namespace Tests\Feature\Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class StagePromptTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $stage         = Stage::factory()->withRounds()->createOne();
        $this->payload = [
            'type'       => NewsPostType::STAGE_POST_TYPE->value,
            'references' => [$stage->id]
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_stage_prompt()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_stage_prompt_with_previous_post()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $post->title));
        self::assertTrue(str_contains($prompt, $post->content));

    }

    public function test_stage_prompt_with_additional_prompt()
    {
        $this->payload['prompt'] = fake()->paragraph();
        $response                = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $this->payload['prompt']));
    }

    public function test_all_placeholders_filled()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $this->payload['prompt']   = fake()->paragraph();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        preg_match_all('(\:[a-z]+)', $prompt, $matches);
        self::assertCount(0, $matches[0]);
    }

    public function test_invalid_stage()
    {
        $this->payload['references'] = [404];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertNotFound();
    }

    public function test_invalid_previous_post()
    {
        $this->payload['previous'] = [404];
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();
    }

    public function test_inactive_stage()
    {
        $stage = Stage::factory()->createOne();
        self::assertTrue($stage->isInactive());

        $this->payload['references'] = [$stage->id];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_ready_stage()
    {
        $stage = Stage::factory()->createOne();
        Round::factory()->for($stage)->withSongs()->createOne([
            'starts_at' => now()->addDay(),
            'ends_at'   => now()->addWeek(),
        ]);
        self::assertTrue($stage->isReady());
        self::assertFalse($stage->isActive());

        $this->payload['references'] = [$stage->id];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_ended_stage()
    {
        $stage = Stage::factory()->createOne();
        Round::factory(2)->for($stage)->ended()->create();
        self::assertTrue($stage->hasEnded());

        $this->payload['references'] = [$stage->id];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_over_stage()
    {
        $stage = Stage::factory()->over()->createOne();
        self::assertTrue($stage->isOver());

        $this->payload['references'] = [$stage->id];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_previous_stages()
    {
        Stage::factory(2)->over()->create();
        $stage = Stage::factory()->withRounds()->createOne();
        self::assertTrue($stage->isActive());

        $this->payload['references'] = [$stage->id];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

}
