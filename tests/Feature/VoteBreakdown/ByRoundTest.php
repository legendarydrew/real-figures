<?php

namespace Tests\Feature\VoteBreakdown;

use App\Facades\ContestFacade;
use App\Facades\VoteBreakdownFacade;
use App\Models\Round;
use App\Models\Stage;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ByRoundTest extends TestCase
{
    use DatabaseMigrations;

    public function test_round_with_no_songs()
    {
        $stage = Stage::factory()->createOne();
        $round     = Round::factory()->for($stage)->createOne();
        $breakdown = VoteBreakdownFacade::forRound($round);

        self::assertEquals($round->id, $breakdown['id']);
        self::assertEquals($round->full_title, $breakdown['title']);
        self::assertEquals($round->votes()->count(), $breakdown['vote_count']);
        self::assertCount(0, $round->songs);
    }

    public function test_round_with_no_outcomes()
    {
        $stage = Stage::factory()->createOne();
        $round     = Round::factory()->for($stage)->withSongs()->withVotes()->createOne();
        $breakdown = VoteBreakdownFacade::forRound($round);

        self::assertEquals($round->id, $breakdown['id']);
        self::assertEquals($round->full_title, $breakdown['title']);
        self::assertEquals($round->votes()->count(), $breakdown['vote_count']);
        self::assertCount($round->songs()->count(), $round->songs);
    }

    public function test_round_with_outcomes()
    {
        $stage = Stage::factory()->createOne();
        $round     = Round::factory()->for($stage)->withSongs()->withVotes()->createOne();
        ContestFacade::buildRoundOutcome($round);
        $breakdown = VoteBreakdownFacade::forRound($round);

        self::assertEquals($round->id, $breakdown['id']);
        self::assertEquals($round->full_title, $breakdown['title']);
        self::assertEquals($round->votes()->count(), $breakdown['vote_count']);
        self::assertCount($round->songs()->count(), $round->songs);
    }
}
