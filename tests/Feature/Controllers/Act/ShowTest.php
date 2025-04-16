<?php

namespace Tests\Feature\Controllers\Act;

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

    public function test_as_guest(): void
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->act->id));
        $response->assertUnauthorized();
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->act->id));
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
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->act->id));
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
