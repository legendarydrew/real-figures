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
        Stage::factory(3)->create();
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/intro'));
    }

    public function test_ready_stage()
    {
        $stages = Stage::factory(3)->create();
        Round::factory()->for($stages[0])->create([
            'starts_at' => now()->addDay()
        ]);
        self::assertTrue($stages[0]->isReady());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
    }

    public function test_active_stage()
    {
        $stages = Stage::factory(3)->create();
        Round::factory()->for($stages[0])->create([
            'starts_at' => now()->subHour(),
            'ends_at'   => now()->addDay()
        ]);
        self::assertTrue($stages[0]->isActive());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
    }

    public function test_ended_stage()
    {
        $stages = Stage::factory(3)->create();
        Round::factory()->for($stages[0])->create([
            'starts_at' => now()->subDay(),
            'ends_at'   => now()
        ]);
        self::assertTrue($stages[0]->hasEnded());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
    }

    public function test_scored_first_stage_with_other_stages()
    {
        $scored_stage = Stage::factory()->withResults()->create();
        self::assertFalse($scored_stage->isOver());

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
    }

    public function test_over_first_stage_with_other_stages()
    {
        $this->createOverStage();
        Stage::factory(2)->create();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
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
        $response->assertInertia(fn(Assert $page) => $page->component('front/home/round'));
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
