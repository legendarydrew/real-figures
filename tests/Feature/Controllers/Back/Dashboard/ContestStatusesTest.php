<?php

namespace Tests\Feature\Controllers\Back\Dashboard;

use App\Enums\ContestStatus;
use App\Facades\ContestFacade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

final class ContestStatusesTest extends TestCase
{
    use DatabaseMigrations;

    public function test_when_contest_is_inactive(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/dashboard-page')
            ->has('contest_status', fn (Assert $page) => $page->where('status', ContestStatus::COMING_SOON)));
    }

    public function test_when_contest_is_counting_down(): void
    {
        Stage::factory()->withRounds(0)->create();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/dashboard-page')
            ->has('contest_status', fn (Assert $page) => $page
                ->where('status', ContestStatus::COUNTDOWN)
                ->has('round')
                ->has('countdown')));
    }

    public function test_when_contest_has_current_round(): void
    {
        Stage::factory()->withRounds(1)->create();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/dashboard-page')
            ->has('contest_status', fn (Assert $page) => $page
                ->where('status', ContestStatus::ACTIVE)
                ->has('round')
                ->has('countdown')
                ->has('acts')));
    }

    public function test_when_contest_is_at_stage_end(): void
    {
        Stage::factory()->withRounds(0, 1)->create();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/dashboard-page')
            ->has('contest_status', fn (Assert $page) => $page
                ->where('status', ContestStatus::JUDGEMENT)
                ->has('round')));
    }

    public function test_when_contest_is_over(): void
    {
        ContestFacade::shouldReceive('isOver')->andReturn(true);
        ContestFacade::partialMock();

        $response = $this->actingAs($this->user)->get(route('admin.dashboard'));
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('back/dashboard-page')
            ->has('contest_status', fn (Assert $page) => $page
                ->where('status', ContestStatus::OVER)));
    }
}
