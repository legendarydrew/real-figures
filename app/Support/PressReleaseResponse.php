<?php

namespace App\Support;

use InvalidArgumentException;

class PressReleaseResponse
{
    public function __construct(public string $title, public string $content)
    {
    }

    public static function fromArray(array $data): self
    {
        // Basic validation (fail fast if AI response is off)
        foreach (['title', 'content'] as $field)
        {
            if (!array_key_exists($field, $data) || !is_string($data[$field]))
            {
                throw new InvalidArgumentException("Invalid or missing field: {$field}");
            }
        }

        return new self(
            title: trim($data['title']),
            content: trim($data['content'])
        );
    }

    public function toArray(): array
    {
        return [
            'title'   => $this->title,
            'content' => $this->content
        ];
    }
}
