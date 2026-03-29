<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;

/**
 * ContestReleaseData
 * Data used for generating a press release about the Contest in general.
 * Our involvement in this News post type should be minimal: all the data should be ascertained
 * from the current state of the Contest.
 */
class ContestReleaseData extends PressReleaseData
{
    public function __construct(
        public string  $title,
        public string  $description,
        public array   $highlights = [],
        public ?string $quote = null,
        public ?string $cta = null,
        public ?string $tone = null,
        public ?string $voice = null,
    )
    {

        parent::__construct(
            type: NewsPostType::CONTEST,
            title: $title,
            description: $this->buildDescription(),
            highlights: $this->buildHighlights(),
            quote: $quote,
            cta: $cta,
            tone: $tone,
            voice: $voice,
        );
    }

    protected function buildDescription(): string
    {
        // TODO before the Contest has started (talk about Acts, Contest purpose).
        // TODO current state of the Contest (previous Stages, current Stage).
        // TODO Contest is over.

        return '';
    }

    protected function buildHighlights(): array
    {
        // TODO before the Contest has started (favourites to win, Act notes).
        // TODO Contest is over (who won, outcomes, votes cast, money raised).

        return [];
    }
}
