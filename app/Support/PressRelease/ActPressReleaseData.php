<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Support\PressReleaseData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

/**
 * ActPressReleaseData
 * Data used for generating a press release about a specific Act.
 * This would be used to help us develop the personality and story behind an Act.
 */
class ActPressReleaseData extends PressReleaseData
{

    protected Collection $acts;

    public function __construct(
        public array   $act_ids,
        public string  $title,
        public string  $description,
        public ?string $quote = null,
    )
    {
        $this->acts = Act::whereIn('id', $this->act_ids)->get();
        parent::__construct(
            type: NewsPostType::ACT,
            title: $this->title ?? $this->acts->implode('full_name', ', '),
            description: $this->description,
            quote: $quote,
        );
    }

    protected function buildDescription(): string
    {
        $output = Lang::get('press-release.act.prefix') . "\n\n";
        $output .= $this->acts->map(fn($act) => $this->getActInformation($act))->implode("\n\n");
        $output .= "\n\n" . $this->description;
        return trim($output);
    }

    protected function buildHighlights(): array
    {
        // TODO mention how they fared in each Stage.
        // TODO mention any Golden Buzzers awarded.
        // TODO mention any accolades (winner/runner-up status).

        return [];
    }

    /**
     * Returns available information about the specified Act.
     *
     * @param Act $act
     * @return string
     */
    protected function getActInformation(Act $act): string
    {
        $act->loadMissing(['profile', 'genres', 'languages', 'members', 'traits', 'notes']);

        $output = [$act->full_name];

        if ($act->genres->count())
        {
            $output[] = "- Genres: " . $act->genres->implode('name', ', ');
        }
        if ($act->languages->count())
        {
            $output[] = "- Languages spoken: " . $act->languages->implode('name', ', ');
        }
        if ($act->members->count())
        {
            $output[] = "- Members: ";
            $output[] = $act->members->map(fn($member) => "  - $member->name ($member->role)")->join("\n");
        }
        if ($act->traits->count())
        {
            $output[] = "- Traits: ";
            $output[] = $act->traits->map(fn($trait) => "  - $trait->trait")->join("\n");
        }
        if ($act->notes->count())
        {
            $output[] = "- Notes: ";
            $output[] = $act->notes->map(fn($note) => "  - $note->note")->join("\n");
        }
        if ($act->profile)
        {
            $output[] = $act->profile->description;
        }

        return implode("\n", $output);
    }

}
