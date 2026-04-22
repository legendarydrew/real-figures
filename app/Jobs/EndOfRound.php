<?php

namespace App\Jobs;

use App\Facades\ContestFacade;
use App\Models\Round;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * EndOfRound
 * This job calculates the respective "scores" for each Song in the provided Round,
 * based on votes. Each Song will have a RoundOutcome created.
 * The job should only run if the Round has ended.
 */
class EndOfRound implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Round $round) {}

    public function handle(): void
    {
        // Check that the Round has both started and ended.
        // This should also catch an edge case where the start time is after the end time.
        // We also check whether RoundOutcomes already exist.
        $round_is_over = $this->round->hasStarted() && $this->round->hasEnded();
        $round_has_outcomes = $this->round->outcomes->isNotEmpty();
        if (!$round_is_over || $round_has_outcomes) {
            $this->delete();

            return;
        }

        ContestFacade::buildRoundOutcomes($this->round);
    }
}
