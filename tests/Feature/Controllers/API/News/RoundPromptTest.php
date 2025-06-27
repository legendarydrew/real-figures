<?php

namespace Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoundPromptTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $stage         = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->withSongs()->started()->createOne();
        $this->payload = [
            'type'       => NewsPostType::ROUND_POST_TYPE->value,
            'references' => [$round->id]
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_round_prompt()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_round_prompt_with_previous_post()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $post->title));
        self::assertTrue(str_contains($prompt, $post->content));

    }

    public function test_round_prompt_with_additional_prompt()
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
        preg_match_all('(\:\w+)', $prompt, $matches);
        self::assertCount(0, $matches[0]);
    }

    public function test_invalid_round()
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

    public function test_round_with_no_songs()
    {
        $stage                       = Stage::factory()->createOne();
        $round                       = Round::factory()->for($stage)->createOne();
        $this->payload['references'] = [$round->id];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertNotFound();
    }

    public function test_round_not_started()
    {
        $stage                       = Stage::factory()->createOne();
        $round                       = Round::factory()->for($stage)->withSongs()->createOne([
            'starts_at' => now()->addDay(),
        ]);
        $this->payload['references'] = [$round->id];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
        // This should be okay, because we might want to announce a Round before it begins.
    }

    public function test_ended_round_with_no_outcomes()
    {
        $stage                       = Stage::factory()->createOne();
        $round                       = Round::factory()->for($stage)->withSongs()->ended()->createOne();
        $this->payload['references'] = [$round->id];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_ended_round_with_outcomes()
    {
        $stage                       = Stage::factory()->createOne();
        $round                       = Round::factory()->for($stage)->withSongs()->ended()->withOutcomes()->createOne();
        $this->payload['references'] = [$round->id];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }
}
