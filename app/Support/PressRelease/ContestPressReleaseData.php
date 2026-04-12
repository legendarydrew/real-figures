<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Facades\ContestFacade;
use App\Models\Act;
use App\Models\RoundVote;
use App\Models\StageWinner;
use App\Support\PressReleaseData;
use Illuminate\Support\Facades\Lang;

/**
 * ContestPressReleaseData
 * Data used for generating a press release about the Contest in general.
 * Our involvement in this News post type should be minimal: all the data should be ascertained
 * from the current state of the Contest.
 */
class ContestPressReleaseData extends PressReleaseData
{
    public function __construct()
    {

        parent::__construct(
            type: NewsPostType::CONTEST,
            title: Lang::get('press-release.contest.title'),
            description: $this->buildDescription(),
            highlights: $this->buildHighlights()
        );
    }

    protected function buildDescription(): string
    {
        $output = [];

        if (ContestFacade::isOver())
        {
            $output[] = Lang::get('press-release.contest.status.over');
        }
        elseif (ContestFacade::isRunning())
        {
            $output[] = Lang::get(ContestFacade::isOnLastStage() ? 'press-release.contest.status.last-stage' : 'press-release.contest.status.started');
            $output[] = Lang::get('press-release.contest.status.current-stage', ['stage' => ContestFacade::getCurrentStage()->title]);
        }
        else
        {
            $output[] = Lang::get('press-release.contest.status.not-started');
            $output[] = Lang::get('press-release.contest.status.acts');
            $acts     = Act::whereHas('songs')
                           ->with(['profile', 'genres', 'languages', 'members', 'traits', 'notes'])
                           ->get();
            foreach ($acts as $act)
            {
                $output[] = $this->getActInformation($act, 1);
            }
        }

        $output[] = Lang::get('press-release.about.information');

        return implode("\n", $output);
    }

    protected function buildHighlights(): array
    {
        $highlights = [];
        if (ContestFacade::isOver())
        {
            $accolades    = StageWinner::get();
            $highlights[] = Lang::get('press-release.contest.highlights.outcome') . "\n" .
                $accolades->map(fn(StageWinner $accolade) => Lang::get('press-release.contest.highlights.accolades.' . ($accolade->is_winner ? 'winner' : 'runner-up'), [
                    'name'  => $accolade->song->act->full_name,
                    'round' => $accolade->round->full_title,
                    'stage' => $accolade->stage->title,
                ]))->implode("\n");

            $highlights[] = Lang::get('press-release.contest.highlights.votes', ['votes' => RoundVote::count()]);
        }
        elseif (ContestFacade::isRunning())
        {
            $favourites = ContestFacade::getCurrentStage()->getActsInvolved()->filter(fn(Act $act) => $act->is_fan_favourite);
            if ($favourites->isNotEmpty())
            {
                $highlights[] = Lang::get('press-release.contest.highlights.favourites') . "\n" .
                    $favourites->map(fn(Act $act) => "  $act->full_name")->implode("\n");
            }
        }

        return $highlights;
    }
}
