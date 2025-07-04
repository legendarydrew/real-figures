<?php

namespace Tests\Feature\Controllers\API\News;

use App\Enums\NewsPostType;
use App\Facades\ContestFacade;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Models\NewsPost;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Lang;
use Tests\TestCase;

class ContestPromptTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'type' => NewsPostType::CONTEST_POST_TYPE->value,
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_contest_prompt()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    public function test_contest_prompt_with_previous_post()
    {
        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $response                  = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $post->title));
        self::assertTrue(str_contains($prompt, $post->content));

    }

    public function test_contest_prompt_with_additional_prompt()
    {
        $this->payload['prompt'] = fake()->paragraph();
        $response                = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, $this->payload['prompt']));
    }

    public function test_while_contest_is_running_first_stage()
    {
        Stage::factory()->withRounds()->create();
        Stage::factory()->create();
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertFalse(str_contains($prompt, Lang::get('press-release.contest.last-stage')));
    }

    public function test_while_contest_is_running_other_stages()
    {
        Stage::factory()->over()->create();
        Stage::factory()->withRounds()->create();
        Stage::factory()->create();

        self::assertFalse(ContestFacade::isOver());
        self::assertFalse(ContestFacade::isOnLastStage());

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertFalse(str_contains($prompt, Lang::get('press-release.contest.last-stage')));
    }

    public function test_while_contest_is_running_last_stage()
    {
        Stage::factory()->over()->create();
        Stage::factory()->withRounds()->create();

        self::assertFalse(ContestFacade::isOver());
        self::assertTrue(ContestFacade::isOnLastStage());

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, Lang::get('press-release.contest.last-stage')));
    }

    public function test_when_contest_is_over()
    {
        Stage::factory()->over()->create();
        self::assertTrue(ContestFacade::isOver());

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, Lang::get('press-release.contest.donations', [
            'currency' => config('contest.donation.currency'),
            'total'    => Donation::sum('amount')
        ])));
        self::assertFalse(str_contains($prompt, Lang::get('press-release.contest.golden-buzzer')));
    }

    public function test_when_contest_is_over_with_donations()
    {
        Stage::factory()->over()->create();
        Donation::factory(10)->create();
        self::assertTrue(ContestFacade::isOver());

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, Lang::get('press-release.contest.donations', [
            'currency' => config('contest.donation.currency'),
            'total'    => Donation::sum('amount')
        ])));
    }

    public function test_when_contest_is_over_with_golden_buzzers()
    {
        Stage::factory()->over()->create();
        GoldenBuzzer::factory(10)->create();
        self::assertTrue(ContestFacade::isOver());

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        self::assertTrue(str_contains($prompt, Lang::get('press-release.contest.golden-buzzers')));
    }

    public function test_contest_announced_all_placeholders_filled()
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

    public function test_contest_running_all_placeholders_filled()
    {
        Stage::factory()->withRounds()->create();

        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $this->payload['prompt']   = fake()->paragraph();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        preg_match_all('(\:\w+)', $prompt, $matches);
        self::assertCount(0, $matches[0]);
    }

    public function test_contest_over_all_placeholders_filled()
    {
        Stage::factory()->over()->create();
        Donation::factory(10)->create();
        GoldenBuzzer::factory(10)->create();

        $post                      = NewsPost::factory()->createOne();
        $this->payload['previous'] = $post->id;
        $this->payload['prompt']   = fake()->paragraph();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $prompt = $response->json('prompt');
        preg_match_all('(\:\w+)', $prompt, $matches);
        self::assertCount(0, $matches[0]);
    }

}
