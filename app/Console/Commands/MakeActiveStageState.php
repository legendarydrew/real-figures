<?php

namespace App\Console\Commands;

use App\Models\Round;
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
class MakeActiveStageState extends Command
{
    protected $signature = 'state:active-stage
    {past? : Number of previous Rounds to create.}
    {future? : Number of future Rounds to create.}';

    protected $description = 'Create an active Stage state.';

    public function handle(): void
    {
        // What we want to do:
        // - Remove any existing Stages;
        // - Create a new Stage with Rounds;
        // - Assign Songs to Rounds.

        $this->info("\nCreating active Stage state...");

        $this->comment('- removing existing Stages');
        Stage::all()->each(function (Stage $stage)
        {
            $stage->delete();
        });

        $this->comment('- creating a new Stage');
        $stage              = Stage::factory()->createOne();
        $past_round_count   = max(0, (int)$this->argument('past')) ?? fake()->numberBetween(0, 5);
        $future_round_count = max(0, (int)$this->argument('future')) ?? fake()->numberBetween(0, 3);

        // Past Rounds.
        if ($past_round_count)
        {
            $this->comment('- creating past Rounds');
            Round::factory($past_round_count)->for($stage)->ended()->withSongs()->create([
                'title' => new Sequence(...array_map(fn($index) => "Round $index", range(1, $past_round_count)))
            ]);
        }

        // Current (active) Round.
        $this->comment('- creating current Round');
        Round::factory()->for($stage)->started()->withSongs()->create([
            'title' => 'Round ' . ($past_round_count + 1),
        ]);

        // Future Rounds.
        if ($future_round_count)
        {
            $this->comment('- creating future Rounds');
            Round::factory($future_round_count)->for($stage)->ready()->withSongs()->create([
                'title' => new Sequence(...array_map(fn($index) => "Round $index", range($past_round_count + 2, $past_round_count + 2 + $future_round_count)))
            ]);
        }

        $this->info("\nCompleted.");

    }
}
