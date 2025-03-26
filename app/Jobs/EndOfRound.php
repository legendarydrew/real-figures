<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * EndOfRound
 * This job will calculate the respective scores for each Song in the provided round,
 * based on votes. Each Song will have a RoundOutcome created.
 *
 * @package App\Jobs
 */
class EndOfRound implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
