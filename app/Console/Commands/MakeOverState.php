<?php

namespace App\Console\Commands;

use App\Facades\ContestFacade;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\RoundSongs;
use App\Models\RoundVote;
use App\Models\SongPlay;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\DB;

/**
 * MakeOverState
 * To help with testing the site functionality, I've created this command to set up a Contest
 * that has ended, with winners and runners-up.
 *
 * @package App\Console\Commands
 */
class MakeOverState extends Command
{
    protected $signature = 'state:over';

    protected $description = 'Create an ended Contest state.';

    public function handle(): void
    {
        // What we want to do:
        // - Remove any existing Stages;
        // - Create a new Stage with Rounds (all of which have ended);
        // - Assign Songs to Rounds;
        // - (optionally) create votes;
        // - (optionally) create Song plays.
        // - create RoundOutcomes
        // - determine winner(s) and runner(s)-up.

        $this->info("\nCreating ended Contest state...");

        $this->comment('- removing existing votes');
        RoundVote::truncate();

        $this->comment('- removing existing RoundOutcomes');
        RoundOutcome::truncate();

        $this->comment('- removing existing winners');
        StageWinner::truncate();

        $this->comment('- removing existing Song plays');
        SongPlay::truncate();

        $this->comment('- removing existing Rounds');
        Round::truncate();

        $this->comment('- removing existing Stages');
        Stage::truncate();

        $this->comment('- creating a new Stage');
        $stage = Stage::factory()->createOne();

        $this->comment('- creating Rounds');
        $round_count = fake()->numberBetween(1, 5);
        Round::factory($round_count)->for($stage)->ended()->withSongs()->withVotes()->create([
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

        $this->comment('- determining winners');
        foreach ($stage->rounds as $round)
        {
            ContestFacade::buildRoundOutcome($round);
        }
        [$winners, $runners_up] = ContestFacade::determineStageWinners($stage);
        DB::transaction(function () use ($stage, $winners, $runners_up)
        {
            foreach ($winners as $winner)
            {
                StageWinner::create([
                    'stage_id'  => $stage->id,
                    'round_id'  => $winner->round_id,
                    'song_id'   => $winner->song_id,
                    'is_winner' => true
                ]);
            }
            foreach ($runners_up as $runner_up)
            {
                StageWinner::create([
                    'stage_id'  => $stage->id,
                    'round_id'  => $runner_up->round_id,
                    'song_id'   => $runner_up->song_id,
                    'is_winner' => false
                ]);
            }
        });

        $this->info("\nCompleted.");

    }
}
