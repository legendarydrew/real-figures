<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\NewsPost;
use DavidBadura\FakerMarkdownGenerator\FakerProvider;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

/**
 * NewsGenerateController
 * This endpoint is responsible for calling OpenAI to generate news posts.
 *
 * @package App\Http\Controllers\API
 */
class NewsGenerateController extends Controller
{
    //

    /**
     * Using the provided information, ask OpenAI to generate content for a News Post.
     * If successful, we will create a new News Post and begin editing it.
     *
     * @param NewsPromptRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewsPromptRequest $request)
    {
        $data = $request->validated();

        // Ensure we have a prompt.
        if (empty($data['prompt']))
        {
            abort(400, 'A prompt is required.');
        }

        // Unless we are in production, use test data.
        if (!app()->isProduction())
        {
            $this->setupTestResponse();
        }

        // Let's go!
        $result = OpenAI::chat()->create([
            'model'    => config('contest.ai.model'),
            'messages' => [
                'role'    => 'user',
                'content' => $data['prompt']
            ]
        ]);

        $usage = $result->usage;
        $json  = $result->choices[0]->message->content;

        $post = NewsPost::factory()->createOne(json_decode($json));
        logger()->info(
            "OpenAI token usage for News Post id $post->id:\n" .
            "  prompt:     {$usage->promptTokens}\n" .
            "  completion: {$usage->completionTokens}\n" .
            "  total:      {$usage->totalTokens}"
        );

        return to_route('admin.news.edit', ['id' => $post->id]);
    }


    protected function setupTestResponse(): void
    {
        fake()->addProvider(new FakerProvider(fake()));

        OpenAI::fake([
            CreateResponse::fake([
                'choices' => [
                    [
                        'title'   => fake()->sentence(),
                        'content' => fake()->markdown(),
                    ],
                ],
            ]),
        ]);
    }

}
