<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/stages';

    protected function setUp(): void
    {
        parent::setUp();

        Stage::factory(10)->create();
    }

    public function test_as_guest()
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    #[Depends('test_as_user')]
    public function test_structure(): void
    {
        $response = $this->actingAs($this->user)->getJson(self::ENDPOINT);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description'
                ]
            ],
            'meta' => [
                'pagination'
            ]
        ]);
    }
}
