<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Act;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
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
        $response->assertInertia(fn(Assert $page) => $page->component('front/acts'));
    }

    public function test_specific_act()
    {
        $acts = Act::factory(2)->withSong('Test')->create();

        $response = $this->get(route('act', ['slug' => $acts->first()->slug]));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/acts')->has('currentAct'));
    }

}
