<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Round;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

// see https://inertiajs.com/testing

class HomeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_stages()
    {
        Stage::truncate();
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/intro'));
    }

    public function test_only_inactive_stages()
    {
        // A Stage is inactive if it has no Rounds.
        $stages = Stage::factory(3)->create();
        self::assertTrue($stages->every(fn(Stage $stage) => $stage->isInactive()));

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/intro'));
    }

    public function test_ready_stage()
    {
        // A Stage is "ready" when it has Rounds, but none of them have started yet.

        $first_stage = Stage::factory()->createOne();
        Round::factory()->for($first_stage)->ready()->create();
        self::assertTrue($first_stage->isReady());

        // With no other Stages.
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/countdown'));

        // With other (inactive) Stages.
        Stage::factory(3)->create();
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/countdown'));
    }

    public function test_active_stage()
    {
        // A Stage is "active" when it has at least one Round currently active.
        $stages = Stage::factory(3)->create();
        Round::factory()->for($stages[0])->started()->create();
        self::assertTrue($stages[0]->isActive());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
    }

    public function test_ended_stage()
    {
        $stages = Stage::factory(3)->create();
        Round::factory()->for($stages[0])->ended()->create();
        self::assertTrue($stages[0]->hasEnded());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));
    }

    public function test_scored_first_stage_with_other_stages()
    {
        $scored_stage = Stage::factory()->withResults()->create();
        self::assertFalse($scored_stage->isOver());

        // With no other Stages...
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));

        // With another (inactive) Stage.
        $inactive_stage = Stage::factory()->create();
        $response       = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));
        $inactive_stage->delete();

        // With another (ready) Stage.
        $ready_stage = Stage::factory()->create();
        Round::factory()->for($ready_stage)->ready()->create();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));

    }

    public function test_over_first_stage_with_other_stages()
    {
        $this->createOverStage();
        Stage::factory(2)->create();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));
    }

    public function test_over_multiple_stages_with_other_stages()
    {
        for ($i = 1; $i <= 3; $i++)
        {
            $this->createOverStage();
        }

        Stage::factory()->createOne();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/stage-end'));
    }

    public function test_all_over_stages()
    {
        for ($i = 1; $i <= 3; $i++)
        {
            $this->createOverStage();
        }

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/over'));
    }

    protected function createOverStage(): void
    {
        $stage = Stage::factory()->withResults()->create();
        StageWinner::create([
            'stage_id' => $stage->id,
            'round_id' => $stage->rounds()->first()->id,
            'song_id'  => $stage->rounds()->first()->songs()->first()->id
        ]);
        self::assertTrue($stage->isOver());
    }
}
