<?php

namespace App\Transformers;

use App\Models\Round;
use League\Fractal\TransformerAbstract;

class RoundAdminTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Round $round): array
    {
        return [
            'id'    => (int)$round->id,
            'title' => $round->title,
            'starts_at' => $round->starts_at->toDateTimeString(),
            'ends_at'   => $round->ends_at->toDateTimeString(),
        ];
    }
}
