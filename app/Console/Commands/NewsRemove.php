<?php

namespace App\Console\Commands;

use App\Models\NewsPost;
use App\Models\NewsPostReference;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('news:remove')]
#[Description('Removes News posts.')]
class NewsRemove extends Command
{
    public function handle(): void
    {
        $this->info("\nRemoving news posts...");

        NewsPostReference::truncate();
        NewsPost::truncate();

        $this->info("\nCompleted.");

    }
}
