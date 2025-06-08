<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * MakeReadyStageState
 * To help with testing the site functionality, I've created this command to set up a Stage
 * with a Round that hasn't yet started. This should display a countdown on the home page.
 *
 * @package App\Console\Commands
 */
class MakeReadyStageState extends Command
{
    protected $signature = 'state:ready-stage
    {rounds? : Number of Rounds to create.}';

    protected $description = 'Create a ready Stage state (display a countdown).';

    public function handle(): void
    {
        // What we want to do:
        // - Remove any existing Stages;
        // - Create a new Stage with Rounds;
        // - Assign Songs to Rounds.

        $this->info("\nCreating ready Stage state...");

        $this->comment('- removing existing Rounds');
        Round::truncate();

        $this->comment('- removing existing Stages');
        Stage::truncate();

        $this->comment('- creating a new Stage');
        $stage              = Stage::factory()->createOne();
        $round_count   = max(1, (int)$this->argument('rounds')) ?? fake()->numberBetween(1, 5);

        $this->comment('- creating Rounds');
        Round::factory($round_count)->for($stage)->future()->withSongs()->create([
            'title' => new Sequence(...array_map(fn($index) => "Round $index", range(1, $round_count)))
        ]);

        $this->info("\nCompleted.");

    }
}
