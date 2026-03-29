<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Support\PressReleaseData;

/**
 * ActPressReleaseData
 * Data used for generating a press release about a specific Act.
 * This would be used to help us develop the personality and story behind an Act.
 */
class ActPressReleaseData extends PressReleaseData
{
    public function __construct(
        public Act $act,
        public string $title,
        public string $description,
        public array $highlights = [],
        public ?string $quote = null,
        public ?string $cta = null,
        public ?string $tone = null,
        public ?string $voice = null,
    ) {

        parent::__construct(
            type: NewsPostType::ACT,
            title: $this->act->full_name,
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
        // TODO relevant meta data (languages, traits, genres, members, notes).
        // TODO use the profile if available.

        return '';
    }

    protected function buildHighlights(): array
    {
        // TODO mention how they fared in each Stage.
        // TODO mention any Golden Buzzers awarded.
        // TODO mention any accolades (winner/runner-up status).

        return [];
    }

}
