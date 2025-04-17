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
        ];
    }
}
