<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/stages/%u';

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
    }

    public function test_as_guest()
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_invalid_row(): void
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_as_user')]
    public function test_structure(): void
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertJsonStructure([
            'id',
            'title',
            'description'
        ]);
    }
}
