<?php

namespace Tests\Feature\Controllers\Back\News;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest(): void
    {
        $response = $this->get(route('admin.news.create'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.news.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/news-edit-page'));
    }
}
