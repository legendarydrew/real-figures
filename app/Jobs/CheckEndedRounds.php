<?php

namespace App\Jobs;

use App\Models\Round;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * CheckEndedRounds
 * This job checks for Rounds that have ended, so outcomes can be generated for them.
 * It was created to address an oversight with the EndOfRound job, which is called
 * for each individual Round.
 *
 * @package App\Jobs
 */
class CheckEndedRounds implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rounds = Round::all();
        $rounds->each(function (Round $round)
        {
            if ($round->hasEnded())
            {
                EndOfRound::dispatchSync($round);
            }
        });
    }
}
