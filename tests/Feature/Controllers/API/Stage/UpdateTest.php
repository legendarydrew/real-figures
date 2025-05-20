<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/stages/%u';

    private Stage $stage;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->createOne();
        $this->payload = [
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function test_as_guest()
    {
        $response = $this->patchJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertRedirect(route('admin.stages'));
    }

    #[Depends('test_as_user')]
    public function test_updates_act()
    {
        $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);

        $this->stage->refresh();
        self::assertEquals($this->payload['title'], $this->stage->title);
        self::assertEquals($this->payload['description'], $this->stage->description);
    }

    #[Depends('test_as_user')]
    public function test_invalid_act()
    {
        $response = $this->actingAs($this->user)->patchJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

}
