<?php

namespace Tests\Feature\Controllers\Back\News;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Testing\Responses\Fixtures\Chat\CreateResponseFixture;
use Tests\TestCase;

class GenerateTest extends TestCase
{
    use DatabaseMigrations;

    protected array                            $payload;
    private \OpenAI\Laravel\Testing\OpenAIFake $aiClient;

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
            'type'   => NewsPostType::CUSTOM_POST_TYPE->value,
            'prompt' => fake()->paragraph()
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(route('news.generate'), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $response->assertRedirect(route('admin.news.edit', ['id' => 1]));
    }

    public function test_creates_post()
    {
        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertInstanceOf(NewsPost::class, $post);
        self::assertNotNull($post->title);
        self::assertNotNull($post->content);
        self::assertNull($post->published_at);
    }

    public function test_empty_prompt()
    {
        $this->payload['prompt'] = null;
        $response                = $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $response->assertBadRequest();
    }

    public function test_non_json_response()
    {
        $choice                                     = CreateResponseFixture::ATTRIBUTES;
        $choice['choices'][0]['message']['content'] = fake()->paragraph();

        $this->aiClient = OpenAI::fake([
            CreateResponse::fake($choice),
        ]);

        $response = $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post     = NewsPost::orderByDesc('id')->first();

        $response->assertRedirect(route('admin.news.edit', ['id' => $post->id]));

        self::assertInstanceOf(NewsPost::class, $post);
        self::assertNotNull($post->title);
        self::assertNotNull($post->content);
        self::assertNull($post->published_at);
    }

    public function test_contest_references()
    {
        $this->payload['type'] = NewsPostType::CONTEST_POST_TYPE->value;
        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertEquals($this->payload['type'], $post->type);
        self::assertCount(0, $post->references);
    }

    public function test_stage_references()
    {
        $stage                       = Stage::factory()->withRounds()->create();
        $this->payload['type']       = NewsPostType::STAGE_POST_TYPE->value;
        $this->payload['references'] = [$stage->id];

        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertInstanceOf(NewsPost::class, $post);
        self::assertEquals($this->payload['type'], $post->type);
        self::assertCount(count($this->payload['references']), $post->references);
        foreach ($post->references as $reference)
        {
            self::assertContains($reference->reference_id, $this->payload['references']);
        }
    }

    public function test_round_references()
    {
        $stage                       = Stage::factory()->withRounds()->create();
        $round                       = Round::factory()->for($stage)->started()->createOne();
        $this->payload['type']       = NewsPostType::ROUND_POST_TYPE->value;
        $this->payload['references'] = [$round->id];

        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertInstanceOf(NewsPost::class, $post);
        self::assertEquals($this->payload['type'], $post->type);
        self::assertCount(count($this->payload['references']), $post->references);
        foreach ($post->references as $reference)
        {
            self::assertContains($reference->reference_id, $this->payload['references']);
        }
    }

    public function test_act_references()
    {
        $acts                        = Act::factory(3)->withSong()->create();
        $this->payload['type']       = NewsPostType::ACT_POST_TYPE->value;
        $this->payload['references'] = $acts->pluck('id')->toArray();

        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertInstanceOf(NewsPost::class, $post);
        self::assertEquals($this->payload['type'], $post->type);
        self::assertCount(count($this->payload['references']), $post->references);
        foreach ($post->references as $reference)
        {
            self::assertContains($reference->reference_id, $this->payload['references']);
        }
    }

    public function test_custom_references()
    {
        $this->payload['type'] = NewsPostType::CUSTOM_POST_TYPE->value;
        $this->actingAs($this->user)->postJson(route('news.generate'), $this->payload);
        $post = NewsPost::orderByDesc('id')->first();

        self::assertEquals($this->payload['type'], $post->type);
        self::assertCount(0, $post->references);
    }

}
