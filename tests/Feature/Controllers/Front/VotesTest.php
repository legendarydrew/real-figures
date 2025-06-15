<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VotesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access_without_stages()
    {
        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_with_inactive_stages()
    {
        Stage::factory()->create();
        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_with_active_stages()
    {
        Stage::factory()->withRounds()->create();
        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_with_ended_stages()
    {
        Stage::factory()->withRounds(0, 2)->create();
        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_with_some_over_stages()
    {
        Stage::factory()->create();
        Stage::factory()->over()->create();
        $response = $this->get(route('votes'));
        $response->assertNotFound();
    }

    public function test_access_with_only_over_stages()
    {
        Stage::factory()->over()->create();
        $response = $this->get(route('votes'));
        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/votes')->has('stages'));
    }

    public function test_only_over_stages()
    {
        $over_stages = Stage::factory(2)->over()->create();
        $response    = $this->get(route('votes'));
        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/votes')
                                                          ->has('stages', $over_stages->count())
                                                          ->etc());
    }

}
