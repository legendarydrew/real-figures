<?php

namespace Tests\Feature\Controllers\Front;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AboutTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertViewIs('front.about');
    }
}
