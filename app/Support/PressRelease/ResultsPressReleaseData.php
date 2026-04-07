<?php

namespace App\Support\PressRelease;

use App\Enums\NewsPostType;
use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Support\PressReleaseData;
use Illuminate\Support\Facades\Lang;

/**
 * ResultsReleaseData
 * Data used for generating a press release about Contest results.
 * Why a specific DTO for results? We can use this for creating press releases about
 * Stage and Round outcomes thus far, and specifically about results.
 */
class ResultsPressReleaseData extends PressReleaseData
{
    public function __construct()
    {
        parent::__construct(
            type: NewsPostType::RESULTS,
            title: Lang::get('press-release.results.title'),
            description: $this->buildDescription(),
            highlights: $this->buildHighlights(),
        );
    }

    protected function buildDescription(): string
    {
        return Stage::all()
                    ->map(function (Stage $stage)
                    {
                        if ($stage->hasEnded())
                        {
                            return Lang::get('press-release.results.stage.ended', ['name' => $stage->title]);
                        }
                        elseif ($stage->hasStarted())
                        {
                            return Lang::get('press-release.results.stage.started', ['name' => $stage->title]);
                        }
                        else
                        {
                            return Lang::get('press-release.results.stage.inactive', ['name' => $stage->title]);
                        }
                    })->implode("\n");
    }

    protected function buildHighlights(): array
    {
        $highlights = collect();

        // Favourites to win.
        $favourites = Act::whereIsFanFavourite(true)->get();
        if ($favourites->isNotEmpty())
        {
            $output = collect(Lang::get('press-release.results.favourites.heading'));
            $output = $output->concat($favourites->map(fn(Act $favourite) => Lang::get('press-release.results.favourites.name', ['name' => $favourite->full_name])));
            $highlights->push($output->implode("\n"));
        }

        // Outcomes from ended Stages.
        $stages = Stage::all()->filter(fn(Stage $stage) => $stage->hasEnded());
        $highlights = $highlights->concat($stages->map(function (Stage $stage)
        {
            $output[] = Lang::get('press-release.results.outcomes.heading', ['name' => $stage->title]);
            $stage->rounds->each(function (Round $round) use (&$output)
            {
                $output[] = Lang::get('press-release.results.outcomes.round', ['round' => $round->title]);
                if ($round->outcomes->some(fn(RoundOutcome $outcome) => $outcome->was_manual))
                {
                    $output[] = Lang::get('press-release.results.outcomes.manual');
                }
                $output[] = $round->outcomes->map(fn(RoundOutcome $outcome) => Lang::get('press-release.results.outcomes.result', [
                    'name'  => $outcome->song->act->full_name,
                    'score' => $outcome->score,
                    'votes' => $outcome->vote_count
                ]))->implode("\n");
            });
            return implode("\n", $output);
        }));

        return $highlights->toArray();
    }
}
