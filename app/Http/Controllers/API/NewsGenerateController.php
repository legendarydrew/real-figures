<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsPromptRequest;
use App\Models\NewsPost;
use App\Models\NewsPostReference;
use DavidBadura\FakerMarkdownGenerator\FakerProvider;
use Illuminate\Http\RedirectResponse;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Testing\Responses\Fixtures\Chat\CreateResponseFixture;

/**
 * NewsGenerateController
 * This endpoint is responsible for calling OpenAI to generate news posts.
 *
 * @package App\Http\Controllers\API
 */
class NewsGenerateController extends Controller
{
    /**
     * Using the provided information, ask OpenAI to generate content for a News Post.
     * If successful, we will create a new News Post and begin editing it.
     *
     * @param NewsPromptRequest $request
     * @return RedirectResponse
     */
    public function store(NewsPromptRequest $request): RedirectResponse
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
        $json = json_decode($result->choices[0]->message->content, true);

        $post = NewsPost::factory()
                        ->unpublished()
                        ->createOne([
                            'type'    => $data['type'],
                            'title'   => $json['title'],
                            'content' => $json['content']
                        ]);
        if (isset($data['references']))
        {
            foreach ($data['references'] as $reference_id)
            {
                NewsPostReference::create([
                    'news_post_id' => $post->id,
                    'reference_id' => $reference_id
                ]);
            }
        }
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

        // https://github.com/openai-php/laravel/issues/95
        $choice                                     = CreateResponseFixture::ATTRIBUTES;
        $choice['choices'][0]['message']['content'] = json_encode([
            'title'   => fake()->sentence(),
            'content' => fake()->markdown(),
        ]);
        OpenAI::fake([
            CreateResponse::fake($choice),
        ]);
    }

}
