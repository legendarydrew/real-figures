<?php

namespace Tests\Feature\Controllers\Back\Acts;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest(): void
    {
        $response = $this->get(route('admin.acts'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.acts'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/acts-page'));
    }
}
