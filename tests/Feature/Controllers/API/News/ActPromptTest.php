<?php

namespace Tests\Feature\Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lang;
use Tests\TestCase;

class ActPromptTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $acts          = Act::factory()->withSong()->withMeta()->create();
        $this->payload = [
            'type'       => NewsPostType::ACT_POST_TYPE->value,
            'references' => $acts->pluck('id')->toArray(),
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_act_prompt()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_act_prompt_with_previous_post()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $post->title));
        self::assertTrue(str_contains($prompt, $post->content));

    }

    public function test_act_prompt_with_additional_prompt()
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

    public function test_invalid_act()
    {
        $this->payload['references'] = [404];
        $response                    = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_invalid_previous_post()
    {
        $this->payload['previous'] = [404];
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnprocessable();
    }

    public function test_no_acts()
    {
        $this->payload['references'] = [];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_invalid_acts()
    {
        $acts                        = Act::factory(3)->create();
        $this->payload['references'] = $acts->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertBadRequest();
    }

    public function test_acts_with_previous_wins()
    {
        $stage                       = Stage::factory()->over()->create();
        $act_ids                     = $stage->getActsInvolved()->pluck('id')->toArray();
        $this->payload['references'] = $act_ids;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, Lang::get('press-release.act.wins')));
    }
}
