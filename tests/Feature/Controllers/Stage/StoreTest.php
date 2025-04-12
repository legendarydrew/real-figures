<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages';

    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payload = [
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function test_as_guest()
    {
        $response = $this->postJson(self::ENDPOINT, $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $response->assertRedirect(route('admin.stages'));
    }

    #[Depends('test_as_user')]
    public function test_creates_stage()
    {
        $this->actingAs($this->user)->postJson(self::ENDPOINT, $this->payload);
        $stage = Stage::whereTitle($this->payload['title'])->first();
        self::assertInstanceOf(Stage::class, $stage);
    }

}
