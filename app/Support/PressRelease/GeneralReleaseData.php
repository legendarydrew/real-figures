<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;
use Illuminate\Support\Facades\Lang;

/**
 * GeneralReleaseData
 * Data used for generating a general press release.
 * This is basically a barebones DTO, where we enter the information to use ourselves.
 */
class GeneralReleaseData extends PressReleaseData
{
    public function __construct(
        public string  $title,
        public string  $description,
        public ?string $quote = null,
        public array   $highlights = [],
    )
    {

        parent::__construct(
            type: NewsPostType::GENERAL,
            title: $title,
            description: $this->description,
            quote: $quote,
            highlights: $this->highlights
        );
    }

    protected function buildDescription(): string
    {
        return $this->description . "\n" . Lang::get('press-release.contest.information');
    }
}
