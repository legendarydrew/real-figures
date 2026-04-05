<?php

namespace App\Http\Controllers\API;

use App\Enums\NewsPostType;
use App\Exceptions\PressReleaseException;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\NewsPost;
use App\Services\PressReleaseAgent;
use App\Support\PressRelease\ActPressReleaseData;
use App\Support\PressRelease\GeneralPressReleaseData;
use App\Support\PressRelease\RoundPressReleaseData;
use App\Support\PressRelease\StagePressReleaseData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * NewsGenerateController
 * This endpoint is responsible for calling OpenAI to generate news posts.
 */
class NewsGenerateController extends Controller
{
    /**
     * Using the provided information, ask OpenAI to generate content for a News Post.
     * If successful, we will create a new News Post and begin editing it.
     *
     * @throws PressReleaseException
     */
    public function store(NewsPromptRequest $request): JsonResponse
    {
        $data    = $request->validated();
        $history = NewsPost::published()->whereIn('id', $data['history'])
                           ->get()
                           ->map(fn(NewsPost $post) => [
                               'title'     => $post->title,
                               'published' => $post->published_at->format('Y-m-d H:i'),
                               'content'   => $post->content
                           ])
                           ->toArray();
        $agent   = new PressReleaseAgent;

        switch ($data['type'])
        {
            case NewsPostType::GENERAL->value:
                $result = $agent->generalPressRelease(
                    new GeneralPressReleaseData(
                        title: $data['title'],
                        description: $data['prompt'],
                        quote: $data['quote'],
                        highlights: $data['highlights'],
                    ),
                    $history
                );
                break;

            case NewsPostType::ACT->value:
                $result = $agent->actPressRelease(
                    new ActPressReleaseData(
                        $data['acts'],
                        title: $data['title'],
                        description: $data['prompt'] ?? '',
                        quote: $data['quote']
                    ),
                    $history
                );
                break;

            case NewsPostType::STAGE->value:
                $result = $agent->stagePressRelease(
                    new StagePressReleaseData($data['stage']), $history
                );
                break;

            case NewsPostType::RESULTS->value:
                $result = $agent->resultsPressRelease();
                break;

            case NewsPostType::ROUND->value:
                $result = $agent->roundPressRelease(
                    new RoundPressReleaseData($data['round'], $history),
                );
                break;

            default:
                abort(Response::HTTP_BAD_REQUEST, 'Unsupported News Post type.');
        }

        // Based on the configured prompt, $result should have:
        // - title (the title of the News post)
        // - content (in Markdown format).
        // We use these to create a new News Post, then redirect to its edit page for scrutiny.

        $post = NewsPost::create($result);

        return response()->json(['id' => $post->id]);
    }

}
