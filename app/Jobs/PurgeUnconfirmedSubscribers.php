<?php

namespace App\Jobs;

use App\Models\Subscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * PurgeUnconfirmedSubscribers
 * This job will remove Subscribers who have not confirmed their subscription after 24 hours.
 *
 * @package App\Jobs
 */
class PurgeUnconfirmedSubscribers implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Subscriber::whereConfirmed(false)
                  ->where('created_at', '<', now()->subDay())
                  ->delete();
    }
}
