<?php

namespace Tests\Feature\Controllers\Front;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RulesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access()
    {
        $response = $this->get(route('rules'));

        $response->assertOk();
        $response->assertViewIs('front.rules');
    }
}
