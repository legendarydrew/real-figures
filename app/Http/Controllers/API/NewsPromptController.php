<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Support\PressRelease\GeneralReleaseData;
use Illuminate\Http\JsonResponse;

class NewsPromptController extends Controller
{
    public function store(NewsPromptRequest $request): JsonResponse
    {
        $data = $request->validated();

        switch ($data['type'])
        {
            case NewsPostType::GENERAL->value:
                // Nothing to do, except ensure there is actually a prompt.
                $prompt = new GeneralReleaseData(
                    title: $data['title'],
                    description: $data['prompt'],
                    quote: $data['quote'],
                    highlights: $data['highlights'],
                );
                break;

            case NewsPostType::ACT->value:
            case NewsPostType::CONTEST->value:
            case NewsPostType::STAGE->value:
            case NewsPostType::ROUND->value:
            case NewsPostType::RESULTS->value:
                abort(412, 'No, not yet.');
                break;
            default:
                abort(400, 'Unsupported News Post type.');
        }

        return response()->json(['prompt' => $prompt->toArray()]);
    }

}
