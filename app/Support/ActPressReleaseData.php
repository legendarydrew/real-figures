<?php

namespace App\Support;

use App\Models\Act;

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
        // TODO build the description from Act information:
        // - meta data (members, genres, languages, traits)
        // - Golden Buzzers awarded
        // - Song accolades.

        parent::__construct(
            type: 'New Entry Spotlight',
            title: $title,
            description: $this->description,
            highlights: $highlights,
            quote: $quote,
            cta: $cta,
            tone: $tone,
            voice: $voice,
        );
    }

}
