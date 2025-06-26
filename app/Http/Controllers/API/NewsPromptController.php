<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\Act;
use App\Models\Round;
use App\Models\RoundOutcome;
use Illuminate\Http\JsonResponse;
use Lang;

class NewsPromptController extends Controller
{

    public function store(NewsPromptRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Here's where things get complicated!
        $prompt_lines   = null;
        $replace_values = [];

        switch ($data['type'])
        {
            case NewsPostType::ROUND_POST_TYPE->value:
                $round          = Round::findOrFail($data['references'][0]);
                $prompt_lines   = $this->buildRoundPrompt($round, $data);
                $replace_values = [
                    'round_name' => $round->full_title
                ];
                break;
            default:
                abort(400, 'Invalid News Post type.');
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

        return response()->json($prompt);
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

    protected function getRoundOutcomeData(Round $round): string
    {
        $outcomes = $round->outcomes->map(fn(RoundOutcome $outcome) => [
            "  - Act: $outcome->song->act->name" . PHP_EOL .
            "    Total score: $outcome->score" . PHP_EOL .
            "    First choice votes: $outcome->first_votes" . PHP_EOL .
            "    Second choice votes: $outcome->second_votes" . PHP_EOL .
            "    Third choice votes: $outcome->third_votes"
        ]);

        return implode(PHP_EOL, ['- Round outcomes:', ...$outcomes]);
    }
}
