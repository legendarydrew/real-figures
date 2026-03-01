<?php

namespace App\Console\Commands;

use App\Models\NewsPost;
use App\Models\NewsPostReference;
use Illuminate\Console\Command;

/**
 * MakeComingSoonState
 * This command puts the site in a "coming soon" state, simply by removing all the Stages and Rounds.
 *
 * @package App\Console\Commands
 */
class NewsCreate extends Command
{
    protected $signature = 'news:create
    {count? : Number of News posts to create.}';

    protected $description = 'Creates News posts.';

    public function handle(): void
    {
        $this->info("\nCreating news posts...");

        $this->comment('- removing existing NewsPosts');
        NewsPostReference::truncate();
        NewsPost::truncate();

        $this->comment('- creating NewsPosts');
        $post_count = max(0, (int)$this->argument('count'));
        if ($post_count === 0) {
            $post_count = fake()->numberBetween(1, 30);
        }
        NewsPost::factory($post_count)->published()->create();

        $this->info("\nCompleted.");

    }
}
