<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Act;
use App\Models\ActProfile;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages/%u';

    private Stage   $stage;
    private array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage     = Stage::factory()->createOne();
        $this->payload = [
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function test_updates_act()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertOk();

        $this->stage->refresh();
        self::assertEquals($this->payload['title'], $this->stage->title);
        self::assertEquals($this->payload['description'], $this->stage->description);
    }

    public function test_invalid_act()
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, 404), $this->payload);
        $response->assertNotFound();
    }

    #[Depends('test_updates_act')]
    public function test_structure(): void
    {
        $response = $this->putJson(sprintf(self::ENDPOINT, $this->stage->id), $this->payload);
        $response->assertJsonStructure([
            'id',
            'title',
            'description'
        ]);
    }

}
