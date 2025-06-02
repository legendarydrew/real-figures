<?php

namespace App\Transformers;

use App\Models\GoldenBuzzer;
use League\Fractal\TransformerAbstract;

class GoldenBuzzerRoundBreakdownTransformer extends TransformerAbstract
{

    public function transform(GoldenBuzzer $buzzer): array
    {
        return [
            'round_title' => $buzzer->round->full_title,
            'amount_raised' => $buzzer->amount_raised
        ];
    }
}
