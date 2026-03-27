<?php

namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class IsOnLastStageTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_stages(): void
    {
        self::assertFalse(ContestFacade::isOnLastStage());
    }

    public function test_inactive_stage(): void
    {
        Stage::factory()->create();

        self::assertFalse(ContestFacade::isOnLastStage());
    }

    public function test_one_stage(): void
    {
        Stage::factory()->withRounds()->create();

        self::assertTrue(ContestFacade::isOnLastStage());
    }

    public function test_first_of_many_stages(): void
    {
        Stage::factory()->withRounds()->create();
        Stage::factory()->create();

        self::assertFalse(ContestFacade::isOver());
    }

    public function test_only_stage(): void
    {
        Stage::factory()->over()->create();

        self::assertTrue(ContestFacade::isOver());
    }

    public function test_last_of_many_stages(): void
    {
        Stage::factory(2)->over()->create();

        self::assertTrue(ContestFacade::isOver());
    }

    public function test_inactive_stages_remaining(): void
    {
        Stage::factory(2)->over()->create();
        Stage::factory()->withRounds()->create();

        self::assertFalse(ContestFacade::isOver());
    }
}
