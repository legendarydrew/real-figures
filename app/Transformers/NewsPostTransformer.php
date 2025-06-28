<?php

namespace App\Transformers;

use App\Models\NewsPost;
use Illuminate\Support\Str;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class NewsPostTransformer extends TransformerAbstract
{

    protected array $availableIncludes = ['content', 'pages'];

    public function transform(NewsPost $post): array
    {
        return [
            'id'           => (int)$post->id,
            'title'        => $post->title,
            'url' => $post->url,
            'content'      => $post->content,
            'excerpt'      => $post->excerpt,
            'created_at'   => $post->created_at->format(config('contest.date_format')),
            'published_at' => $post->published_at?->format(config('contest.date_format')) ?? null,
            'updated_at'   => $post->updated_at->format(config('contest.date_format')),
        ];
    }

    /**
     * Include the News Post's content as HTML for display on the main site.
     *
     * @param NewsPost $post
     * @return Primitive|null
     */
    public function includeContent(NewsPost $post): ?Primitive
    {
        return $this->primitive(Str::markdown($post->content));
    }

    /**
     * Include the News Post's previous and next pages, if available.
     *
     * @param NewsPost $post
     * @return Primitive|null
     */
    public function includePages(NewsPost $post): ?Primitive
    {
        $previous_post = $post->previousPost();
        $next_post     = $post->nextPost();

        return $this->primitive([
            'previous' => $previous_post ? [
                'title' => $previous_post->title,
                'url'   => $previous_post->url
            ] : null,
            'next'     => $next_post ? [
                'title' => $next_post->title,
                'url'   => $next_post->url
            ] : null,
        ]);
    }

}
