<?php

namespace Tests\Unit\RoundOutcome;

use App\Models\RoundOutcome;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ScoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test_no_votes()
    {
        $outcome = RoundOutcome::factory()->make([
            'first_votes'  => 0,
            'second_votes' => 0,
            'third_votes'  => 0,
        ]);

        self::assertEquals(0, $outcome->score);
    }

    public function test_with_votes()
    {
        $outcome = RoundOutcome::factory()->make();

        $total_score = ($outcome->first_votes * config('contest.points.0'))
            + ($outcome->second_votes * config('contest.points.1'))
            + ($outcome->third_votes * config('contest.points.2'));

        self::assertEquals($total_score, $outcome->score);

    }
}
