<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Song;
use App\Models\StageWinner;
use App\Support\PressReleaseData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

/**
 * StageReleaseData
 * Data used for generating a press release about a specific Round in the Contest.
 */
class RoundPressReleaseData extends PressReleaseData
{
    private Round      $round;
    private Collection $acts;

    public function __construct(
        public int    $round_id,
        public string $description
    )
    {
        $this->round = Round::findOrFail($this->round_id);
        $this->acts  = $this->round->songs->map(fn(Song $song) => $song->act);

        parent::__construct(
            type: NewsPostType::ROUND,
            title: Lang::get('press-release.round.title', ['round' => $this->round->full_title]),
            description: $this->buildDescription() . (!empty($description) ? "\n\n$description" : ''),
            highlights: $this->buildHighlights()
        );
    }

    protected function buildDescription(): string
    {
        $output = [];

        $favourites = $this->acts->filter(fn(Act $act) => $act->is_fan_favourite);

        $output[] = Lang::get('press-release.round.acts') . "\n" .
            $this->acts->map(fn(Act $act) => $this->getActInformation($act, 1))
                       ->implode("\n");

        if ($favourites->isNotEmpty())
        {
            $output[] = Lang::get('press-release.round.favourites') . "\n" .
                $favourites->map(fn(Act $act) => "  {$act->full_name}")->implode("\n");
        }

        if ($this->round->stage->hasEnded())
        {
            $output[] = Lang::get('press-release.round.outcome.heading');
            if ($this->round->outcomes->some(fn(RoundOutcome $outcome) => $outcome->was_manual))
            {
                $output[] = Lang::get('press-round.outcome.manual');
            }
            $output[] = $this->round->outcomes->map(fn(RoundOutcome $outcome) => Lang::get('press-release.round.outcome.result', [
                'name'  => $outcome->song->act->full_name,
                'score' => $outcome->score,
                'votes' => $outcome->vote_count
            ]))->implode("\n");
        }

        return implode("\n", $output);
    }

    protected function buildHighlights(): array
    {
        return $this->acts->flatMap(fn(Act $act) => $act->accolades->map(fn(StageWinner $row) => Lang::get('press-release.round.accolades.winner', [
            'name'  => $act->full_name,
            'round' => $this->round->full_title,
            'stage' => $this->round->stage->title
        ]))
        )->toArray();
    }
}
