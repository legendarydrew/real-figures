<?php

namespace Tests\Feature\Controllers\Front;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ContestTest extends TestCase
{
    use DatabaseMigrations;

    public function test_access(): void
    {
        $response = $this->get(route('contest'));

        $response->assertOk();
    }

    public function test_before_contest_begins(): void
    {
        $response = $this->get(route('contest'));

        $response->assertOk();
        $response->assertViewIs('front.contest.inactive');
    }

    public function test_contest_round_countdown(): void
    {
        $stage = Stage::factory()->create();
        Round::factory()->create([
            'stage_id' => $stage->id,
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addWeek(),
        ]);

        $response = $this->get(route('contest'));

        $response->assertOk();
        $response->assertViewIs('front.contest.countdown');
    }

    public function test_contest_has_active_round(): void
    {
        Stage::factory()->withRounds(1)->create();
        $response = $this->get(route('contest'));

        $response->assertOk();
        $response->assertViewIs('front.contest.active-round');
    }

    public function test_contest_stage_end(): void
    {
        Stage::factory()->withRounds(0, 1)->create();
        $response = $this->get(route('contest'));

        $response->assertOk();
        $response->assertViewIs('front.contest.stage-end');
    }

    public function test_contest_is_over(): void
    {
        Stage::factory()->over()->createOne();
        $response = $this->get(route('contest'));

        $response->assertOk();
        $response->assertViewIs('front.contest.over');
    }
}
