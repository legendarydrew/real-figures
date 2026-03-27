<?php

namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class IsOverTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_stages(): void
    {
        self::assertFalse(ContestFacade::isOver());
    }

    public function test_inactive_stage(): void
    {
        Stage::factory()->create();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_ready_stage(): void
    {
        Stage::factory()->withRounds(started_count: 0, ended_count: 0)->create();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_active_first_stage(): void
    {
        Stage::factory()->withRounds(started_count: 2, ended_count: 0)->create();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_ended_stage(): void
    {
        Stage::factory()->withRounds(started_count: 0, ended_count: 2)->create();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_over_stages(): void
    {
        Stage::factory()->over()->create()->toArray();
        Stage::factory()->create()->toArray();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_contest_is_over(): void
    {
        Stage::factory(2)->over()->create()->toArray();

        self::assertTrue(ContestFacade::isOver());
    }
}
