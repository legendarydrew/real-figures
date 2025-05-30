<?php

namespace App\Services;

use App\Exceptions\DataException;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\Stage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoundAllocate
{

    /**
     * Create one or more Rounds for a Stage, allocating the specified Songs to each Round.
     * This will also remove any existing Rounds.
     *
     * @param Stage       $stage
     * @param Collection  $songs
     * @param int|null    $songs_per_round
     * @param Carbon|null $round_start
     * @param string      $round_duration
     * @return void
     * @throws Exception
     */
    public function songs(Stage $stage, Collection $songs, int $songs_per_round = null, Carbon $round_start = null, string $round_duration = '6 days'): void
    {
        if ($songs->count() === 0)
        {
            throw new DataException('No Songs were provided.');
        }
        elseif ($songs->count() < 2)
        {
            throw new DataException('At least two Songs are required.');
        }
        elseif ($stage->hasStarted())
        {
            throw new DataException('Stage has already started.');
        }

        if ($songs_per_round > 1)
        {
            $songs->shuffle();
            $song_chunks = $songs->chunk($songs_per_round, false);
        }
        else
        {
            $song_chunks = [$songs];
        }

        $round_start = $round_start?->clone() ?? Carbon::now()->addDay()->startOfHour();

        DB::transaction(function () use ($stage, $song_chunks, $round_start, $round_duration)
        {
            // Remove all existing Rounds.
            Round::whereStageId($stage->id)->delete();

            $interval = \DateInterval::createFromDateString($round_duration);
            foreach ($song_chunks as $index => $song_chunk)
            {
                $round = Round::factory()->create([
                    'stage_id'  => $stage->id,
                    'title' => count($song_chunks) === 1 ? 'Final Round' : "Round " . ($index + 1),
                    'starts_at' => $round_start,
                    'ends_at'   => $round_start->clone()->add($interval),
                ]);
                foreach ($song_chunk as $song)
                {
                    RoundSongs::create([
                        'round_id' => $round->id,
                        'song_id'  => $song->id,
                    ]);
                }
                $round_start->add($interval)->addDay();
            }
        });
    }
}
