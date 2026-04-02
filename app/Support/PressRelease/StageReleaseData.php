<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;

/**
 * StageReleaseData
 * Data used for generating a press release about a Stage in the Contest.
 */
class StageReleaseData extends PressReleaseData
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
            type: NewsPostType::STAGE,
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
        // TODO first Stage of the Contest (mention that Rounds were drawn at random).
        // TODO second Stage of the Contest (the finals).
        // TODO a description of the Stage.

        return '';
    }

    protected function buildHighlights(): array
    {
        // TODO before the Stage has started (how many Acts, whether there were more than expected, manual voting).
        // TODO first Stage of the Contest (who was drawn against whom, favourites to win).
        // TODO second Stage of the Contest (e.g.who made it, favourites to win).
        // TODO Stage is over (manual voting required or not, votes cast, Golden Buzzers awarded).

        return [];
    }
}
