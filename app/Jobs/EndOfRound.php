<?php

namespace App\Jobs;

use App\Models\Round;
use App\Models\RoundOutcome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

/**
 * EndOfRound
 * This job will calculate the respective "scores" for each Song in the provided round,
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
    public function __construct(private Round $round)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check that the round has started and ended.
        // This should also catch an edge case where the start time is after the end time.
        if (!($this->round->starts_at->isPast() && $this->round->ends_at->isPast()))
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

        // Check if there are votes for the round. If there are, create RoundOutcomes.
        // Bear in mind that it's possible for a Song not to have received any votes!
        if ($this->round->votes()->count())
        {
            $votes          = $this->round->votes;
            $first_choices  = array_count_values($votes->pluck('first_choice_id')->toArray());
            $second_choices = array_count_values($votes->pluck('second_choice_id')->toArray());
            $third_choices  = array_count_values($votes->pluck('third_choice_id')->toArray());

            DB::transaction(function () use ($first_choices, $second_choices, $third_choices)
            {
                foreach ($this->round->songs as $song)
                {
                    RoundOutcome::factory()
                                ->for($this->round)
                                ->for($song)
                                ->create([
                                    'first_votes'  => $first_choices[$song->id] ?? 0,
                                    'second_votes' => $second_choices[$song->id] ?? 0,
                                    'third_votes'  => $third_choices[$song->id] ?? 0,
                                ]);
                }
            });
        }
    }
}
