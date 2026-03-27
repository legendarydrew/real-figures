<?php

namespace Tests\Feature\Controllers\Back;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest()
    {
        $response = $this->get(route('admin.analytics'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.analytics'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/analytics-page'));
    }
}
