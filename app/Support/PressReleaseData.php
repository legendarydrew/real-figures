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
        public ?string $cta = null,
        public ?string $tone = null,
        public ?string $voice = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type->value,
            'title' => $this->title,
            'description' => $this->description,
            'highlights' => $this->highlights,
            'quote' => $this->quote,
            'cta' => $this->cta,
            'tone' => $this->tone,
            'voice' => $this->voice,
        ], fn ($value) => $value !== null);
    }
}
