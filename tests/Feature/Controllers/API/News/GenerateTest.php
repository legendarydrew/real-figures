<?php

namespace Tests\Feature\Controllers\API\News;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Laravel\Testing\OpenAIFake;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Testing\Responses\Fixtures\Chat\CreateResponseFixture;
use Tests\TestCase;

final class GenerateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/generate';

    private OpenAIFake $aiClient;

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        fake()->addProvider(new \DavidBadura\FakerMarkdownGenerator\FakerProvider(fake()));

        $choice                                     = CreateResponseFixture::ATTRIBUTES;
        $choice['choices'][0]['message']['content'] = json_encode([
            'title'   => fake()->sentence(),
            'content' => fake()->markdown(),
        ]);

        $this->aiClient = OpenAI::fake([
            CreateResponse::fake($choice),
        ]);

        $this->payload = [
            'type'       => NewsPostType::GENERAL->value,
            'title'      => fake()->sentence(),
            'prompt'     => fake()->sentence(),
            'quote'      => fake()->sentence(),
            'history'    => [],
            'highlights' => fake()->sentences()
        ];
    }

    public function test_as_guest(): void
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_contest_prompt(): void
    {
        $this->payload['type'] = NewsPostType::CONTEST->value;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::CONTEST->value, $post->type);
    }

    public function test_stage_prompt(): void
    {
        $stage                  = Stage::factory()->createOne();
        $this->payload['type']  = NewsPostType::STAGE->value;
        $this->payload['stage'] = $stage->id;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::STAGE->value, $post->type);
    }

    public function test_round_prompt(): void
    {
        $stage                  = Stage::factory()->createOne();
        $round                  = Round::factory()->for($stage)->createOne();
        $this->payload['type']  = NewsPostType::ROUND->value;
        $this->payload['round'] = $round->id;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::ROUND->value, $post->type);
    }

    public function test_act_prompt(): void
    {
        $acts                  = Act::factory(4)->create();
        $this->payload['type'] = NewsPostType::ACT->value;
        $this->payload['acts'] = $acts->pluck('id')->toArray();

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::ACT->value, $post->type);
    }

    public function test_results_prompt(): void
    {
        $this->payload['type'] = NewsPostType::RESULTS->value;

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::RESULTS->value, $post->type);
    }

    public function test_general_with_prompt(): void
    {
        $this->payload = [
            'type'       => NewsPostType::GENERAL->value,
            'title'      => fake()->sentence(),
            'prompt'     => fake()->sentence(),
            'quote'      => fake()->sentence(),
            'history'    => [],
            'highlights' => fake()->sentences()
        ];

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();

        $post = NewsPost::findOrFail($response->json('id'));
        self::assertEquals(NewsPostType::GENERAL->value, $post->type);

    }

    public function test_non_compliant_responses(): void
    {
        // We're looking for a JSON object from OpenAI.
        $choice                                     = CreateResponseFixture::ATTRIBUTES;
        $choice['choices'][0]['message']['content'] = fake()->paragraph;

        $this->aiClient = OpenAI::fake([
            CreateResponse::fake($choice),
            CreateResponse::fake($choice),
            CreateResponse::fake($choice),
        ]);

        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertServerError();
    }

}
