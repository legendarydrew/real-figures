<?php


namespace Tests\Unit\Contest;

use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BuildRoundOutcomeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_round_with_votes()
    {
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->withSongs()->withVotes()->create();
        ContestFacade::buildRoundOutcome($round);

        $outcomes = RoundOutcome::whereRoundId($round->id)->get();
        self::assertCount($round->songs()->count(), $outcomes);
    }

    public function test_round_with_no_votes()
    {
        $stage = Stage::factory()->createOne();
        $round = Round::factory()->for($stage)->withSongs()->create();
        ContestFacade::buildRoundOutcome($round);

        $outcomes = RoundOutcome::whereRoundId($round->id)->get();
        self::assertCount(0, $outcomes);
    }
}
