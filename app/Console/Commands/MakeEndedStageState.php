<?php

namespace App\Console\Commands;

use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\SongPlay;
use App\Models\Stage;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * MakeActiveStageState
 * To help with testing the site functionality, I've created this command to set up a Stage
 * with an active Round, and optionally some previous Rounds.
 *
 * @package App\Console\Commands
 */
class MakeEndedStageState extends Command
{
    protected $signature = 'state:ended-stage
    {rounds? : Number of Rounds to create.}';

    protected $description = 'Create an ended Stage state.';

    public function handle(): void
    {
        // What we want to do:
        // - Remove any existing Stages;
        // - Create a new Stage with Rounds (all of which have ended);
        // - Assign Songs to Rounds;
        // - (optionally) create votes;
        // - (optionally) create Song plays.

        $this->info("\nCreating ended Stage state...");

        $this->comment('- removing existing votes');
        RoundVote::truncate();

        $this->comment('- removing existing Song plays');
        SongPlay::truncate();

        $this->comment('- removing existing Rounds');
        Round::truncate();

        $this->comment('- removing existing Stages');
        Stage::truncate();

        $this->comment('- creating a new Stage');
        $stage = Stage::factory()->createOne();

        $this->comment('- creating Rounds');
        $round_count = max(0, (int)$this->argument('rounds'));
        if (!$round_count)
        {
            $round_count = fake()->numberBetween(1, 5);
        }
        Round::factory($round_count)->for($stage)->ended()->withSongs()->create([
            'title' => new Sequence(...array_map(fn($index) => "Round $index", range(1, $round_count)))
        ]);

        $this->comment('- creating Song plays');
        $song_ids = array_unique(RoundSongs::pluck('song_id')->toArray());
        foreach (range(-6, 0) as $i)
        {
            foreach ($song_ids as $song_id)
            {
                SongPlay::create([
                    'played_on'  => now()->subDays(abs($i))->startOfDay(),
                    'song_id'    => $song_id,
                    'play_count' => fake()->numberBetween(1, 5000)
                ]);
            }
        }

        $this->comment('- creating votes');
        foreach ($stage->rounds as $round)
        {
            if (fake()->boolean(80))
            {
                $song_ids   = $round->songs()->pluck('songs.id')->toArray();
                $vote_count = fake()->numberBetween(1, 300);
                foreach (range(1, $vote_count) as $_)
                {
                    $voted_for_songs = fake()->randomElements($song_ids, 3);
                    RoundVote::create([
                        'round_id'         => $round->id,
                        'first_choice_id'  => $voted_for_songs[0],
                        'second_choice_id' => $voted_for_songs[1],
                        'third_choice_id'  => $voted_for_songs[2]
                    ]);
                }
            }
        }

        $this->comment('- determining outcomes');
        foreach ($stage->rounds as $round)
        {
            ContestFacade::buildRoundOutcome($round);
        }

        $this->info("\nCompleted.");

    }
}
