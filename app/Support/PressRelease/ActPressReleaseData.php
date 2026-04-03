<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\Round;
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
            highlights: $this->buildHighlights(),
            quote: $quote
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
        $output = [];

        // Rounds the Acts were involved in. (No duplication.)
        $rounds = Round::get()
                       ->filter(fn(Round $round) => $round->stage->hasEnded())
                       ->filter(fn(Round $round) => $round->songs->whereIn('act_id', $this->acts->pluck('id'))->count());
        if ($rounds->isNotEmpty()) {
            $output[] = "Outcome of Rounds they were involved in:\n".
                $rounds->map(function (Round $round)
                {
                    $outcomes = $round->outcomes()->scoreOrder()->get();
                    return "  {$round->full_title}\n"  .
                        $outcomes->map(fn($outcome) => "    {$outcome->song->act->full_name} scored $outcome->score point(s)")->implode("\n");
                })->implode("\n\n");
        }

        $this->acts->each(function ($act) use (&$output)
        {
            $highlights = "";

            // Accolades.
            $act->accolades->each(function ($accolade) use (&$highlights)
            {
                $highlights .= "  " . ($accolade->is_winner ? "Winner of" : "Runner-up in") . " {$accolade->round->full_title}\n";
            });

            // Any awarded Golden Buzzers (but only for ended Stages).
            $buzzers         = $act->goldenBuzzers->filter(fn($buzzer) => $buzzer->stage->hasEnded());
            $grouped_buzzers = $buzzers->groupBy(fn($buzzer) => $buzzer->stage->id);
            $grouped_buzzers->each(function ($group) use (&$highlights)
            {
                $highlights .= "  Was awarded {$group->count()} Golden Buzzers in {$group->first()->stage->title}.\n";
            });

            if (!empty($highlights)) {
                $output[] = "For $act->full_name:\n" . $highlights;
            }
        });

        return $output;
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
            $output[] = "- Profile: ";
            $output[] = $act->profile->description;
        }

        return implode("\n", $output);
    }

}
