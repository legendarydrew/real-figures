<?php

namespace App\Support;

use App\Enums\NewsPostType;

abstract class PressReleaseData
{
    public function __construct(
        public NewsPostType $type,  // the type of generated News post
        public string $title,
        public string $description,
        public array $highlights = [],
        public ?string $quote = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type->value,
            'title' => $this->title,
            'description' => $this->buildDescription(),
            'highlights' => $this->highlights,
            'quote' => $this->quote,
        ], fn ($value) => $value !== null);
    }

    /**
     * Returns the description to use for the press release.
     * Here we can automatically add any necessary information.
     *
     * @return string
     */
    protected function buildDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns a list of significant bits of information for shaping the content of the press release.
     *
     * @return array
     */
    protected function buildHighlights(): array
    {
        return [];
    }

}
