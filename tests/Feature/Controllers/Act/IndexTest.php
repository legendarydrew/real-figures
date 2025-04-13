<?php

namespace Tests\Feature\Controllers\Act;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/acts';

    protected function setUp(): void
    {
        parent::setUp();

        Act::factory(10)->create();
        Act::factory(10)->withProfile()->create();
    }

    public function test_as_guest(): void
    {
        $response = $this->getJson(self::ENDPOINT);
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
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
                    'name',
                    'slug',
                    'has_profile'
                ]
            ],
            'meta' => [
                'pagination'
            ]
        ]);
    }
}
