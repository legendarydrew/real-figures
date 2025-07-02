<?php

namespace Tests\Feature\Controllers\API\Stage;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class RoundsTest extends TestCase
{
    use DatabaseMigrations;

    protected const string ENDPOINT = '/api/stages/%u/rounds';

    private Stage $stage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stage = Stage::factory()->withRounds()->create();
    }

    public function test_as_guest()
    {
        $response = $this->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertUnauthorized();
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, $this->stage->id));
        $response->assertInertia(fn(Assert $page) => $page->component('back/stages')->has('rounds'));
    }

    #[Depends('test_as_user')]
    public function test_invalid_stage(): void
    {
        $response = $this->actingAs($this->user)->getJson(sprintf(self::ENDPOINT, 404));
        $response->assertNotFound();
    }

}
