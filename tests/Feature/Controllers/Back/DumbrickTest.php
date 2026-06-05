<?php

namespace Tests\Feature\Controllers\Back;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

final class DumbrickTest extends TestCase
{
    use DatabaseMigrations;

    public function test_as_guest(): void
    {
        $response = $this->get(route('admin.dumbrick'));

        $response->assertRedirectToRoute('login');
    }

    public function test_as_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.dumbrick'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/dumbrick-page'));
    }

    #[Depends('test_as_user')]
    public function test_with_no_current_round(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.dumbrick'));
        $response->assertInertia(fn(Assert $page) => $page->component('back/dumbrick-page')
                                                          ->where('currentRound', null));
    }

    #[Depends('test_as_user')]
    public function test_with_current_round(): void
    {
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->started()->createOne();


        $response = $this->actingAs($this->user)->get(route('admin.dumbrick'));
        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('back/dumbrick-page')
                                                          ->where('currentRound', $round->full_title));

    }
}
