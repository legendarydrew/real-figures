<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Support\PressReleaseData;

/**
 * GeneralReleaseData
 * Data used for generating a general press release.
 * This is basically a barebones DTO, where we enter the information to use ourselves.
 */
class GeneralReleaseData extends PressReleaseData
{
    public function __construct(
        public string $title,
        public string $description,
    )
    {

        parent::__construct(
            type: NewsPostType::GENERAL,
            title: $title,
            description: $this->description
        );
    }
}
