<?php

namespace App\Transformers;

use App\Models\Donation;
use App\Models\GoldenBuzzer;
use League\Fractal\TransformerAbstract;

class DonationTransformer extends TransformerAbstract
{

    public function transform(Donation|GoldenBuzzer $donation): array
    {
        return [
            'id'         => (int)$donation->id,
            'name'         => $donation->is_anonymous ? trans('anonymous') : $donation->name,
            'created_at'   => $donation->created_at->format(config('contest.date_format')),
            'is_anonymous' => $donation->is_anonymous,
        ];
    }
}
