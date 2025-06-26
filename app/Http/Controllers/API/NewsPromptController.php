<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\Round;
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

    protected function buildRoundPrompt(Round $round, array $data): array
    {
        $lines = [];

        if ($round->hasEnded())
        {
            // Include:
            // - information about the Round
            // - information about the Acts
            // - the outcomes of the Round
            // - who won the Round.
            $lines += Lang::get('press-release.round.ended');
        }
        elseif ($round->hasStarted())
        {
            // Include:
            // - information about the Round
            // - information about the Acts.
            $lines += Lang::get('press-release.round.started');
        }

        return $lines;
    }
}
