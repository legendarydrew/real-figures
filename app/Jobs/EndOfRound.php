<?php

namespace App\Jobs;

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

        // Check that the Round has Votes for the round. If it has, create RoundOutcomes.
        // Bear in mind that it's possible for a Song not to have received any votes!
        if ($this->round->votes()->count())
        {
            DB::transaction(function ()
            {
                $votes          = $this->round->votes;
                $first_choices  = array_count_values($votes->pluck('first_choice_id')->toArray());
                $second_choices = array_count_values($votes->pluck('second_choice_id')->toArray());
                $third_choices  = array_count_values($votes->pluck('third_choice_id')->toArray());

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
