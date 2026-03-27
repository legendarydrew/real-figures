<?php

namespace Tests\Feature\Controllers\Front;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class DonorWallTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access(): void
    {
        $response = $this->get(route('donate'));

        $response->assertOk();
        $response->assertViewIs('front.donate');
        $response->assertViewHas(['donations', 'buzzers']);
    }
}
