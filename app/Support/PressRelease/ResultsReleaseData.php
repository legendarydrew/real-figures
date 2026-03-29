<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;

/**
 * ResultsReleaseData
 * Data used for generating a press release about Contest results.
 * Why a specific DTO for results? We can use this for creating press releases about
 * Stage and Round outcomes thus far, and specifically about results.
 */
class ResultsReleaseData extends PressReleaseData
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
            type: NewsPostType::RESULTS,
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
        // TODO which Stages have ended, and any outstanding Stages.

        return '';
    }

    protected function buildHighlights(): array
    {
        // TODO outcomes from ended Stages.
        return [];
    }
}
