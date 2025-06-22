<?php

namespace App\Jobs;

use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

/**
 * EndOfRound
 * This job calculates the respective "scores" for each Song in the provided Round,
 * based on votes. Each Song will have a RoundOutcome created.
 * The job should only run if the Round has ended.
 *
 * @package App\Jobs
 */
class EndOfRound implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Round $round)
    {
    }

    public function handle(): void
    {
        // Check that the Round has both started and ended.
        // This should also catch an edge case where the start time is after the end time.
        $now = now();
        if (!($this->round->starts_at < $now && $this->round->ends_at < $now))
        {
            $this->delete();
            return;
        }

        // Check for existing RoundOutcomes. If there are any, there is nothing to do.
        if ($this->round->outcomes()->count())
        {
            $this->delete();
            return;
        }

        ContestFacade::buildRoundOutcome($this->round);
    }
}
