<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\Round;
use App\Models\Song;
use App\Models\Stage;
use App\Support\PressReleaseData;
use Illuminate\Support\Facades\Lang;

/**
 * StagePressReleaseData
 * Data used for generating a press release about a Stage in the Contest.
 */
class StagePressReleaseData extends PressReleaseData
{
    protected bool $is_first_stage = false;
    protected bool $is_last_stage  = false;
    private Stage  $stage;

    public function __construct(
        public int $stage_id,
        string $description,
    )
    {
        $this->stage          = Stage::findOrFail($this->stage_id);
        $stage_ids            = Stage::pluck('id')->toArray();
        $this->is_first_stage = $stage_ids[0] === $this->stage->id;
        $this->is_last_stage  = end($stage_ids) === $this->stage->id;

        parent::__construct(
            type: NewsPostType::STAGE,
            title: Lang::get('press-release.stage.title', ['stage' => $this->stage->title]),
            description: $this->buildDescription() . (!empty($description) ? "\n\n$description" : ''),
            highlights: $this->buildHighlights(),
        );
    }

    protected function buildDescription(): string
    {
        $output   = [];
        $output[] = Lang::get('press-release.stage.description.title', ['stage' => $this->stage->title]);
        $output[] = $this->stage->description;
        if ($this->is_first_stage)
        {
            $output[] = Lang::get('press-release.stage.description.first-stage');
        }
        if ($this->is_last_stage)
        {
            $output[] = Lang::get('press-release.stage.description.last-stage');
        }
        $output[] = Lang::get('press-release.stage.description.buzzer-perks', ['perks' => $this->stage->golden_buzzer_perks]);

        $output[] = '';

        $this->stage->rounds->each(function (Round $round) use (&$output)
        {
            $output[] = $round->title;
            $round->songs->each(function (Song $song) use (&$output)
            {
                $output[] = "  {$song->act->full_name}";
            });
        });

        return implode("\n", $output);
    }

    protected function buildHighlights(): array
    {
        $highlights = [];

        if ($this->stage->hasEnded())
        {
            $highlights[] = Lang::get('press-release.stage.highlight.ended');
            if ($this->stage->requiresManualVote())
            {
                $highlights[] = Lang::get('press-release.stage.highlight.manual-vote');
            }
        }
        elseif ($this->stage->hasStarted())
        {
            $highlights[] = Lang::get('press-release.stage.highlight.started');
        }
        else
        {
            $highlights[] = Lang::get('press-release.stage.highlight.not-started');
        }

        $acts       = $this->stage->getActsInvolved();
        $favourites = $acts->filter(fn(Act $act) => $act->is_fan_favourite);

        $highlights[] = Lang::get('press-release.stage.highlight.acts') . "\n" .
            $acts->map(fn(Act $act) => $this->getActInformation($act,1))
                 ->implode("\n");

        if ($favourites->isNotEmpty())
        {
            $highlights[] = Lang::get('press-release.stage.highlight.favourites') . "\n" .
                $favourites->map(fn(Act $act) => "  {$act->full_name}")->implode("\n");
        }
        else
        {
            $highlights[] = Lang::get('press-release.stage.highlight.no-favourites');
        }

        return $highlights;
    }
}
