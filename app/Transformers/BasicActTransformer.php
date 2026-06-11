<?php

namespace App\Transformers;

use App\Models\Act;
use League\Fractal\TransformerAbstract;

class BasicActTransformer extends TransformerAbstract
{
    public function transform(Act $act): array
    {
        return [
            'id'       => (int)$act->id,
            'name'     => $act->name,
            'subtitle' => $act->subtitle,
            'slug'     => $act->slug,
            'image'    => $act->image,
        ];
    }
}
