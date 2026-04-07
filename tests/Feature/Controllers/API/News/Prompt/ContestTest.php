<?php

namespace Tests\Feature\Controllers\API\News\Prompt;

use App\Enums\NewsPostType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class ContestTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = 'api/news/prompt';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payload = [
            'type'       => NewsPostType::CONTEST->value,
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

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_structure()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertJsonStructure([
            'prompt' => [
                'type',
                'title',
                'description',
                'highlights',
                'history'
            ]
        ]);
    }

    #[Depends('test_as_user')]
    public function test_prompt_matches_data(): void
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);

        $response->assertJsonPath('prompt.type', $this->payload['type']);
    }
}
