<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\Act;
use App\Models\Donation;
use App\Models\NewsPost;
use App\Models\Round;
use App\Models\RoundOutcome;
use App\Models\Stage;
use App\Models\StageWinner;
use Illuminate\Http\JsonResponse;
use Lang;

class NewsPromptController extends Controller
{

    public function store(NewsPromptRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Here's where things get complicated!
        $prompt_lines = [];
        $replace_values = [];

        // Include a reference to a previous News Post, if specified.
        if (isset($data['previous']))
        {
            $prompt_lines                       = Lang::get('press-release.previous');
            $previous_post                      = NewsPost::findOrFail($data['previous']);
            $replace_values['previous_title']   = $previous_post->title;
            $replace_values['previous_content'] = $previous_post->content;
        }

        switch ($data['type'])
        {
            case NewsPostType::CUSTOM_POST_TYPE->value:
                // Nothing to do, except ensure there is actually a prompt.
                if (!isset($data['prompt']))
                {
                    abort(400, 'A prompt is required for a custom post.');
                }
                break;
            case NewsPostType::CONTEST_POST_TYPE->value:
                $prompt_lines = array_merge($prompt_lines, $this->buildContestPrompt($data));
                break;
            case NewsPostType::STAGE_POST_TYPE->value:
                $stage        = Stage::findOrFail($data['references'][0]);
                $prompt_lines = array_merge($prompt_lines, $this->buildStagePrompt($stage, $data));
                break;
            case NewsPostType::ROUND_POST_TYPE->value:
                $round = Round::whereHas('songs')->findOrFail($data['references'][0]);
                $prompt_lines                 = array_merge($prompt_lines, $this->buildRoundPrompt($round, $data));
                $replace_values['round_name'] = $round->full_title;
                break;
            default:
                abort(400, 'Unsupported News Post type.');
        }

        // Add any additional prompts.
        if (isset($data['prompt']))
        {
            $prompt_lines[] = $data['prompt'];
        }

        // Add output requirements.
        $prompt_lines = array_merge($prompt_lines, Lang::get('press-release.output'));

        $prompt = implode(PHP_EOL, $prompt_lines);

        // Replace placeholders with corresponding values.
        $prompt = __($prompt, [
            'contest_host' => 'CATAWOL Records',
            'contest_name' => 'Real Figures Don\'t F.O.L.D',
            ...$replace_values
        ]);

        return response()->json(['prompt' => $prompt]);
    }

    /**
     * Build a prompt to use for generating a News Post about the Contest.
     *
     * @param array $data
     * @return array
     */
    protected function buildContestPrompt(array $data): array
    {
        if (ContestFacade::isOver())
        {
            $lines = $this->buildContestOverPrompt();
        }
        elseif (ContestFacade::isRunning())
        {
            $lines = $this->buildContestRunningPrompt();
        }
        else
        {
            $lines = Lang::get('press-release.contest.announce');
        }

        // Insert Act information.
        $competing_acts = Act::whereHas('songs')->get()->map(fn(Act $act) => $this->getActData($act))->toArray();
        if ($index = array_search(':acts', $lines))
        {
            array_splice($lines, $index, 1, $competing_acts);
        }

        return $lines;
    }

    /**
     * Build a prompt to use for generating a News Post about the specified Stage.
     *
     * @param Stage $stage
     * @param array $data
     * @return array
     */
    public function buildStagePrompt(Stage $stage, array $data): array
    {
        $lines = [];

        if ($stage->isOver())
        {
            $lines = $this->getStageOverPrompt($lines, $stage);
        }
        elseif ($stage->hasEnded())
        {
            $lines = $this->getStageEndedPrompt($lines, $stage);
        }
        elseif ($stage->isActive())
        {
            $lines = $this->getStageActivePrompt($lines, $stage);
        }
        elseif ($stage->isReady())
        {
            $lines = $this->getStageReadyPrompt($lines, $stage);
        }
        else
        {
            abort(400, 'This Stage is inactive.');
        }

        // In both cases, include:
        // - a list of Acts participating in this Stage;
        // - any information about the involved Acts' performance in previous Stages.

        $lines[] = Lang::get('press-release.stage.stage-acts');
        $acts    = $stage->getActsInvolved();
        foreach ($acts as $act)
        {
            $lines[] = "- $act->name";
        }

        $previous_wins = StageWinner::where('stage_id', '<', $stage->id)->get();
        if ($previous_wins->isNotEmpty())
        {
            $lines[] = Lang::get('press-release.stage.previous-results');
            foreach ($previous_wins as $row)
            {
                $rank    = $row->is_winner ? 'Winner' : 'Runner-up';
                $lines[] = "- $row->song->act->name: $rank ($row->round->full_title)";
            }
        }

        return $lines;
    }

    /**
     * Build a prompt to use for generating a News Post about the specified Round.
     *
     * @param Round $round
     * @param array $data
     * @return array
     */
    protected function buildRoundPrompt(Round $round, array $data): array
    {
        $lines = [];

        if ($round->hasEnded())
        {
            // Include the outcomes of the Round.
            $lines   = Lang::get('press-release.round.ended');
            $lines[] = $this->getRoundOutcomeData($round);
        }
        elseif ($round->hasStarted())
        {
            $lines = Lang::get('press-release.round.started');
        }

        // Act information.
        foreach ($round->songs as $song)
        {
            $lines[] = $this->getActData($song->act);
        }

        return $lines;
    }

    /**
     * Returns information about an Act to include in the prompt.
     *
     * @param Act $act
     * @return string
     */
    protected function getActData(Act $act): string
    {
        $act->loadMissing(['languages', 'traits', 'genres', 'members', 'notes']);

        $output = [
            "- Act: $act->name",
            "  Is a Fan Favourite: " . ($act->is_fan_favourite ? 'Yes' : 'No')
        ];

        if ($act->members->isNotEmpty())
        {
            $output[] = "  Members:";
            foreach ($act->members as $member)
            {
                $output[] = "$member->name ($member->role)";
            }
        }

        if ($act->languages->isNotEmpty())
        {
            $output[] = "  Spoken languages: " . implode(', ', $act->languages()->pluck('name')->toArray());
        }

        if ($act->genres->isNotEmpty())
        {
            $output[] = "  Genre(s): " . implode(', ', $act->genres()->pluck('name')->toArray());
        }

        if ($act->traits->isNotEmpty())
        {
            $output[] = "  Personality traits:";
            foreach ($act->traits as $trait)
            {
                $output[] = $trait->trait;
            }
        }
        if ($act->notes->isNotEmpty())
        {
            $output[] = "  Notes:";
            foreach ($act->notes as $note)
            {
                $output[] = $note->note;
            }
        }

        return implode("\n", $output);
    }

    /**
     * Returns a series of RoundOutcomes formatted for the prompt.
     *
     * @param Round $round
     * @return string
     */
    protected function getRoundOutcomeData(Round $round): string
    {
        $outcomes = $round->outcomes->map(fn(RoundOutcome $outcome) => "  - Act: $outcome->song->act->name" . PHP_EOL .
            "    Total score: $outcome->score" . PHP_EOL .
            "    First choice votes: $outcome->first_votes" . PHP_EOL .
            "    Second choice votes: $outcome->second_votes" . PHP_EOL .
            "    Third choice votes: $outcome->third_votes" . PHP_EOL .
            "    Was judged?: " . ($outcome->was_manual ? 'Yes' : 'No')
        );

        return implode(PHP_EOL, ['- Round outcomes:', ...$outcomes]);
    }

    /**
     * @return array|string
     */
    protected function buildContestOverPrompt(): string|array
    {
        $lines = Lang::get('press-release.contest.over');

        // Include:
        // - an overview of the results
        // - any donations raised
        // - any Golden Buzzers, and which Acts were supported.

        $results = ContestFacade::overallWinners();
        $lines[] = Lang::get('press-release.contest.overall-winners');
        foreach ($results['winners'] as $row)
        {
            $lines[] = "- {$row['act']['name']}";
        }
        $lines[] = Lang::get('press-release.contest.runners-up');
        foreach ($results['runners_up'] as $row)
        {
            $lines[] = "- {$row['act']['name']}";
        }

        $golden_buzzer_acts = Act::whereHas('goldenBuzzers')->get();
        if ($golden_buzzer_acts->isNotEmpty())
        {
            $lines[] = Lang::get('press-release.contest.golden-buzzers');
            foreach ($golden_buzzer_acts as $act)
            {
                $lines[] = "  - $act->name";
            }
        }

        $lines[] = Lang::get('press-release.contest.donations', [
            'currency' => config('contest.donation.currency'),
            'total'    => Donation::sum('amount')
        ]);
        return $lines;
    }

    /**
     * @return array|string
     */
    protected function buildContestRunningPrompt(): string|array
    {
        $lines = Lang::get('press-release.contest.running');

        if (ContestFacade::isOnLastStage())
        {
            $lines[] = Lang::get('press-release.contest.last-stage');
        }

        // Include information about the current Round, if present.
        $current_round = ContestFacade::getCurrentStage()?->getCurrentRound();
        $stage_winners = StageWinner::whereIsWinner(true)->get();

        if ($current_round)
        {
            $lines[] = Lang::get('press-release.contest.current-round', ['round_title' => $current_round->full_title]);
            foreach ($current_round->songs as $song)
            {
                $lines[] = "- {$song->act->name}";
            }
        }

        if ($stage_winners->isNotEmpty())
        {
            $lines[] = Lang::get('press-release.contest.previous-stage-winners');
            foreach ($stage_winners as $row)
            {
                $lines[] = "- {$row->song->act->name} ({$row->round->full_title})";
            }
        }
        return $lines;
    }

    /**
     * @param array $lines
     * @param Stage $stage
     * @return array|string
     */
    protected function getStageOverPrompt(array $lines, Stage $stage): string|array
    {
        $lines = array_merge($lines, Lang::get('press-release.stage.over'));

        // Include the winners and runners-up of the Stage.
        $lines[] = Lang::get('press-release.stage.outcome');
        foreach ($stage->winners as $row)
        {
            $rank    = $row->is_winner ? 'Winner' : 'Runner-up';
            $lines[] = "- {$row->song->act->name}: $rank ({$row->round->title})";
        }
        return $lines;
    }

    /**
     * @param array $lines
     * @param Stage $stage
     * @return array|string
     */
    protected function getStageEndedPrompt(array $lines, Stage $stage): string|array
    {
        $lines = array_merge($lines, Lang::get('press-release.stage.ended'));

        // Include the number of votes in each Round.
        $lines[] = Lang::get('press-release.stage.round-votes');
        foreach ($stage->rounds as $round)
        {
            $lines[] = "- {$round->title}: {$round->votes->count()}";
        }
        return $lines;
    }

    /**
     * @param array $lines
     * @param Stage $stage
     * @return array|string
     */
    protected function getStageActivePrompt(array $lines, Stage $stage): string|array
    {
        $lines = array_merge($lines, Lang::get('press-release.stage.active'));

        // Include information about the current Round in this Stage.
        $current_round = $stage->getCurrentRound();
        $lines[]       = Lang::get('press-release.stage.current-round', ['round_title' => $current_round->full_title]);
        $lines[]       = Lang::get('press-release.stage.current-round-acts');
        foreach ($current_round->songs as $song)
        {
            $lines[] = "- {$song->act->name}";
        }
        $lines[] = Lang::get('press-release.stage.current-round-ends', ['round_end' => $current_round->ends_at->toISOString()]);
        return $lines;
    }

    /**
     * @param array $lines
     * @param Stage $stage
     * @return array|string
     */
    protected function getStageReadyPrompt(array $lines, Stage $stage): string|array
    {
        $lines = array_merge($lines, Lang::get('press-release.stage.ready'));

        // In this case, we would want to know which Acts are in each Stage.
        $lines[] = Lang::get('press-release.stage.round-breakdown');
        foreach ($stage->rounds as $round)
        {
            $lines[] = "- $round->title";
            foreach ($round->songs as $song)
            {
                $lines[] = "  - $song->act->name (performing $song->name)";
            }
        }
        return $lines;
    }
}
