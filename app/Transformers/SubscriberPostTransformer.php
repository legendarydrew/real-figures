<?php

namespace App\Transformers;

use App\Models\Subscriber;
use App\Models\SubscriberPost;
use League\Fractal\TransformerAbstract;

class SubscriberPostTransformer extends TransformerAbstract
{

    public function transform(SubscriberPost $post): array
    {
        return [
            'id'         => (int)$post->id,
            'title'      => $post->title,
            'sent_count' => $post->sent_count,
            'created_at' => $post->created_at->format(config('contest.date_format')),
        ];
    }
}
