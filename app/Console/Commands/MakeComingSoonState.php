<?php

namespace App\Console\Commands;

use App\Models\Round;
use App\Models\Stage;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * MakeComingSoonState
 * This command puts the site in a "coming soon" state, simply by removing all the Stages and Rounds.
 */
#[Signature('state:coming-soon')]
#[Description('Puts the site in a "coming soon" state.')]
class MakeComingSoonState extends Command
{
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
