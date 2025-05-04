<?php

namespace App\Transformers;

use App\Models\Subscriber;
use League\Fractal\TransformerAbstract;

class SubscriberTransformer extends TransformerAbstract
{

    public function transform(Subscriber $subscriber): array
    {
        return [
            'id'         => (int)$subscriber->id,
            'email'      => $subscriber->email,
            'created_at' => $subscriber->created_at->format(config('contest.date_format')),
            'updated_at' => $subscriber->updated_at->format(config('contest.date_format')),
        ];
    }
}
