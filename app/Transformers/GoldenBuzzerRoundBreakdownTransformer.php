<?php

namespace App\Transformers;

use App\Models\GoldenBuzzer;
use League\Fractal\TransformerAbstract;

class GoldenBuzzerRoundBreakdownTransformer extends TransformerAbstract
{

    public function transform(GoldenBuzzer $buzzer): array
    {
        return [
            'round_id' => $buzzer->round_id,
            'round_title' => $buzzer->round->full_title,
            'amount_raised' => sprintf("%s %0.2d", config('contest.donation.currency'), $buzzer->amount_raised)
        ];
    }
}
