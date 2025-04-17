<?php

namespace App\Transformers;

use App\Models\Round;
use League\Fractal\TransformerAbstract;

class RoundAdminTransformer extends TransformerAbstract
{
    public function transform(Round $round): array
    {
        return [
            'id'        => (int)$round->id,
            'title'     => $round->title,
            'starts_at' => $round->starts_at->format('F d Y H:i'),
            'ends_at'   => $round->ends_at->format('F d Y H:i'),
        ];
    }
}
