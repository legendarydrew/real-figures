<?php

namespace Tests\Feature\Controllers\Stage;

use App\Models\Act;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/stages';

    protected function setUp(): void
    {
        parent::setUp();

        Stage::factory(10)->create();
    }

    public function test_access(): void
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertOk();
    }

    #[Depends('test_access')]
    public function test_structure(): void
    {
        $response = $this->getJson(self::ENDPOINT);
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
