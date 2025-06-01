<?php

namespace App\Transformers;

use App\Models\GoldenBuzzer;
use League\Fractal\TransformerAbstract;

class GoldenBuzzerTransformer extends TransformerAbstract
{

    public function transform(GoldenBuzzer $donation): array
    {
        $donation->load(['round', 'song']);
        return [
            'id'           => (int)$donation->id,
            'name'         => $donation->is_anonymous ? trans('anonymous') : $donation->name,
            'created_at'   => $donation->created_at->format(config('contest.date_format')),
            'is_anonymous' => $donation->is_anonymous,
            'message'      => $donation->message ?? null,
            'amount'       => sprintf("%s %s", $donation->currency, number_format($donation->amount, 2)),
            'round'        => $donation->round->full_title,
            'song'         => [
                'title'    => $donation->song->title,
                'language' => $donation->song->language,
                'act_id'   => (int)$donation->song->act_id,
                'act'      => [
                    'name'  => $donation->song->act->name,
                    'image' => $donation->song->act->image
                ],
            ]
        ];
    }
}
