<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\NewsPost;
use App\Support\PressRelease\ActPressReleaseData;
use App\Support\PressRelease\ContestPressReleaseData;
use App\Support\PressRelease\GeneralPressReleaseData;
use App\Support\PressRelease\ResultsPressReleaseData;
use App\Support\PressRelease\RoundPressReleaseData;
use App\Support\PressRelease\StagePressReleaseData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NewsPromptController extends Controller
{
    public function store(NewsPromptRequest $request): JsonResponse
    {
        $data = $request->validated();

        switch ($data['type'])
        {
            case NewsPostType::GENERAL->value:
                // Nothing to do, except ensure there is actually a prompt.
                $prompt = new GeneralPressReleaseData(
                    title: $data['title'],
                    description: $data['prompt'],
                    quote: $data['quote'],
                    highlights: $data['highlights'],
                );
                break;

            case NewsPostType::ACT->value:
                $prompt = new ActPressReleaseData(
                    $data['acts'],
                    title: $data['title'],
                    description: $data['prompt'] ?? '',
                    quote: $data['quote'],
                );
                break;

            case NewsPostType::CONTEST->value:
                $prompt = new ContestPressReleaseData();
                break;

            case NewsPostType::ROUND->value:
                $prompt = new RoundPressReleaseData(
                    $data['round']
                );
                break;

            case NewsPostType::STAGE->value:
                $prompt = new StagePressReleaseData(
                    $data['stage']
                );
                break;

            case NewsPostType::RESULTS->value:
                $prompt = new ResultsPressReleaseData();
                break;

            default:
                abort(Response::HTTP_BAD_REQUEST, 'Unsupported News Post type.');
        }

        $history = NewsPost::published()->whereIn('id', $data['history'])
                           ->get()
                           ->map(fn(NewsPost $post) => sprintf('%s - %s',
                               $post->published_at->format(config('contest.format.full-date')),
                               $post->title));

        return response()->json(['prompt' => [...$prompt->toArray(), 'history' => $history]]);
    }

}
