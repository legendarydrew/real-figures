<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;

/**
 * StageReleaseData
 * Data used for generating a press release about a specific Round in the Contest.
 */
class RoundReleaseData extends PressReleaseData
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
            type: NewsPostType::ROUND,
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
        // TODO which Acts are in this Round.

        return '';
    }

    protected function buildHighlights(): array
    {
        // TODO Act accolades from previous Rounds.
        // TODO any rivalries.
        return [];
    }
}
