<?php

namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

final class OverallWinnersTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_stages(): void
    {
        self::assertNull(ContestFacade::overallWinners());
    }

    public function test_contest_not_over(): void
    {
        Stage::factory()->withRounds()->create();

        self::assertNull(ContestFacade::overallWinners());
    }

    public function test_contest_is_over(): void
    {
        Stage::factory()->over()->create();

        $results = ContestFacade::overallWinners();
        self::assertIsArray($results);
        self::assertArrayHasKey('winners', $results);
        self::assertArrayHasKey('runners_up', $results);
    }
}
