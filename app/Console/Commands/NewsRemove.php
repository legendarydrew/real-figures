<?php

namespace App\Console\Commands;

use App\Models\NewsPost;
use App\Models\NewsPostReference;
use Illuminate\Console\Command;

class NewsRemove extends Command
{
    protected $signature = 'news:remove';

    protected $description = 'Removes News posts.';

    public function handle(): void
    {
        $this->info("\nRemoving news posts...");

        NewsPostReference::truncate();
        NewsPost::truncate();

        $this->info("\nCompleted.");

    }
}
