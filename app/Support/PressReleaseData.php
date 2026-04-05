<?php

namespace App\Support;

use App\Enums\NewsPostType;
use App\Models\Act;
use Illuminate\Support\Facades\Lang;

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

    /**
     * Returns available information about the specified Act.
     *
     * @param Act  $act
     * @param bool $with_profile
     * @return string
     */
    protected function getActInformation(Act $act, bool $with_profile = true, int $indent = 0): string
    {
        $act->loadMissing(['profile', 'genres', 'languages', 'members', 'traits', 'notes']);

        $indent_spaces = str_repeat('  ', $indent);
        $output = [$indent_spaces . $act->full_name];

        if ($act->genres->count())
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.genres', ['genres' => $act->genres->implode('name', ', ')]);
        }
        if ($act->languages->count())
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.languages', ['languages' => $act->languages->implode('name', ', ')]);
        }
        if ($act->members->count())
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.members');
            $output[] = $act->members->map(fn($member) => $indent_spaces . "  - $member->name ($member->role)")->join("\n");
        }
        if ($act->traits->count())
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.traits');
            $output[] = $act->traits->map(fn($trait) => $indent_spaces . "  - $trait->trait")->join("\n");
        }
        if ($act->notes->count())
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.notes');
            $output[] = $act->notes->map(fn($note) => $indent_spaces . "  - $note->note")->join("\n");
        }
        if ($with_profile && $act->profile)
        {
            $output[] = $indent_spaces . Lang::get('press-release.act.profile');
            $output[] = $indent_spaces . $act->profile->description;
        }

        return implode("\n", $output);
    }

}
