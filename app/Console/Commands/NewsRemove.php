<?php

namespace App\Console\Commands;

use App\Models\NewsPost;
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

        NewsPost::truncate();

        $this->info("\nCompleted.");

    }
}
