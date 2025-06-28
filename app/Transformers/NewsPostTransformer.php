<?php

namespace App\Transformers;

use App\Models\NewsPost;
use League\Fractal\TransformerAbstract;

class NewsPostTransformer extends TransformerAbstract
{

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
}
