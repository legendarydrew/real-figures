<?php

namespace Tests\Feature\Controllers\Back;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SubscribersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest(): void
    {
        $response = $this->get(route('admin.subscribers'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.subscribers'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/subscribers-page'));
    }

    public function test_email_filter(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.subscribers', ['filter' => ['email' => fake()->email]]));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/subscribers-page'));
    }
}
