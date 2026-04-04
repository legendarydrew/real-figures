<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Support\PressRelease\ActPressReleaseData;
use App\Support\PressRelease\GeneralPressReleaseData;
use App\Support\PressRelease\ResultsPressReleaseData;
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
            case NewsPostType::STAGE->value:
            case NewsPostType::ROUND->value:
                abort(Response::HTTP_PRECONDITION_FAILED, 'No, not yet.');
                break;

            case NewsPostType::RESULTS->value:
                $prompt = new ResultsPressReleaseData();
                break;

            default:
                abort(Response::HTTP_BAD_REQUEST, 'Unsupported News Post type.');
        }

        return response()->json(['prompt' => $prompt->toArray()]);
    }

}
