<?php

namespace Controllers\Act;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use DatabaseMigrations;

    private const string ENDPOINT = '/api/acts/%u';

    private Act $act;

    protected function setUp(): void
    {
        parent::setUp();

        $this->act = Act::factory()->withProfile()->create();
    }

    public function test_valid_row(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->act->id));
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
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->act->id));
        $response->assertJsonStructure([
            'id',
            'name',
            'slug',
            'has_profile',
            'profile' => [
                'description'
            ]
        ]);
    }
}
