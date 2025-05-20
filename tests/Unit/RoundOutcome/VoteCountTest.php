<?php

namespace Tests\Unit\RoundOutcome;

use App\Models\RoundOutcome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class VoteCountTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_votes()
    {
        $outcome = RoundOutcome::factory()->make([
            'first_votes'  => 0,
            'second_votes' => 0,
            'third_votes'  => 0,
        ]);

        self::assertEquals(0, $outcome->vote_count);
    }

    public function test_with_votes()
    {
        $outcome = RoundOutcome::factory()->make();

        $total_votes = $outcome->first_votes + $outcome->second_votes + $outcome->third_votes;

        self::assertEquals($total_votes, $outcome->vote_count);

    }
}
