<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_acts()
    {
        $response = $this->get(route('acts'));

        $response->assertNotFound();
    }

    public function test_no_acts_with_songs()
    {
        Act::factory(2)->create();
        $response = $this->get(route('acts'));

        $response->assertNotFound();
    }

    public function test_acts_with_songs()
    {
        Act::factory(2)->withSong('Test')->create();
        $response = $this->get(route('acts'));

        $response->assertOk();
        $response->assertViewIs('front.acts');
        $response->assertViewHas('acts');
    }
}
