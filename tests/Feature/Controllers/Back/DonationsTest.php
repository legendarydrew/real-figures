<?php

namespace Tests\Feature\Controllers\Back;

use App\Models\Donation;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class DonationsTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest(): void
    {
        $response = $this->get(route('admin.donations'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.donations'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/donations-page'));
    }

    #[Depends('test_as_user')]
    public function test_with_donations(): void
    {
        Stage::factory()->withRounds()->create();
        Donation::factory(21)->create();

        $response = $this->actingAs($this->user)->get(route('admin.donations'));

        $response->assertOk();
    }
}
