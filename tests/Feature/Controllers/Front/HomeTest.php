<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

// see https://inertiajs.com/testing

class HomeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access()
    {
        Stage::truncate();
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertViewIs('front.home');
    }

}
