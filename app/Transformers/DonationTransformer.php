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
            'email'      => $donation->email,
            'amount'     => "$donation->currency $donation->amount",
            'donated_at' => $donation->created_at->format(config('contest.date_format'))
        ];
    }
}
