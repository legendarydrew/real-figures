<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages/%u';

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->create();
    }

    public function test_valid_row(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertOk();
    }

    public function test_invalid_row(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

    #[Depends('test_valid_row')]
    public function test_structure(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertJsonStructure([
            'id',
            'title',
            'description'
        ]);
    }
}
