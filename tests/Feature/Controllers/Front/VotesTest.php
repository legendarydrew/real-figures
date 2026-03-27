<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class VotesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access_when_not_over()
    {
        Stage::factory()->create();

        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_when_over()
    {
        Stage::factory()->over()->create();

        $response = $this->get(route('votes'));
        $response->assertOk();
        $response->assertViewHas('stages');
    }
}
