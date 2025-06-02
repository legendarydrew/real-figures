<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Console\Command;

/**
 * MakeComingSoonState
 * This command puts the site in a "coming soon" state, simply by removing all the Stages and Rounds.
 *
 * @package App\Console\Commands
 */
class MakeComingSoonState extends Command
{
    protected $signature = 'state:coming-soon';

    protected $description = 'Puts the site in a "coming soon" state.';

    public function handle(): void
    {
        // What we want to do:
        // - Remove any existing Rounds;
        // - Remove any existing Stages.

        $this->info("\nCreating \"coming soon\" state...");

        $this->comment('- removing existing Rounds');
        Round::truncate();

        $this->comment('- removing existing Stages');
        Stage::truncate();

        $this->info("\nCompleted.");

    }
}
