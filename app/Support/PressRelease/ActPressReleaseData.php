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
        if ($rounds->isNotEmpty())
        {
            $output[] = Lang::get('press-release.act.outcomes.heading') . "\n" .
                $rounds->map(function (Round $round)
                {
                    $outcomes = $round->outcomes()->scoreOrder()->get();
                    return Lang::get('press-release.act.outcomes.round', ['round' => $round->full_title]) . "\n" .
                        $outcomes->map(fn($outcome) => Lang::get('press-release.act.outcomes.result', [
                            'name'  => $outcome->song->act->full_name,
                            'score' => $outcome->score
                        ]))->implode("\n");
                })->implode("\n\n");
        }

        $this->acts->each(function ($act) use (&$output)
        {
            $highlights = "";

            // Accolades.
            $act->accolades->each(function ($accolade) use (&$highlights)
            {
                $highlights .= Lang::get(
                        $accolade->is_winner ?
                            'press-release.act.accolades.winner' :
                            'press-release.act.accolades.runner-up',
                        ['round' => $accolade->round->full_title]) . "\n";
            });

            // Any awarded Golden Buzzers (but only for ended Stages).
            $buzzers         = $act->goldenBuzzers->filter(fn($buzzer) => $buzzer->stage->hasEnded());
            $grouped_buzzers = $buzzers->groupBy(fn($buzzer) => $buzzer->stage->id);
            $grouped_buzzers->each(function ($group) use (&$highlights)
            {
                $highlights .= Lang::get('press-release.act.buzzers', [
                        'count' => $group->count(),
                        'stage' => $group->first()->stage->title
                    ]) . "\n";
            });

            if (!empty($highlights))
            {
                $output[] = Lang::get('press-release.act.highlight', ['name' => $act->full_name]) . "\n" . $highlights;
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
            $output[] = Lang::get('press-release.act.genres', ['genres' => $act->genres->implode('name', ', ')]);
        }
        if ($act->languages->count())
        {
            $output[] = Lang::get('press-release.act.languages', ['languages' => $act->languages->implode('name', ', ')]);
        }
        if ($act->members->count())
        {
            $output[] = Lang::get('press-release.act.members');
            $output[] = $act->members->map(fn($member) => "  - $member->name ($member->role)")->join("\n");
        }
        if ($act->traits->count())
        {
            $output[] = Lang::get('press-release.act.traits');
            $output[] = $act->traits->map(fn($trait) => "  - $trait->trait")->join("\n");
        }
        if ($act->notes->count())
        {
            $output[] = Lang::get('press-release.act.notes');
            $output[] = $act->notes->map(fn($note) => "  - $note->note")->join("\n");
        }
        if ($act->profile)
        {
            $output[] = Lang::get('press-release.act.profile');
            $output[] = $act->profile->description;
        }

        return implode("\n", $output);
    }

}
