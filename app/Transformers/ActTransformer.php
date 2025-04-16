<?php

namespace App\Transformers;

use App\Models\Act;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ActTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['profile'];

    public function transform(Act $act): array
    {
        return [
            'id'          => (int)$act->id,
            'name'        => $act->name,
            'slug'        => $act->slug,
            'has_profile' => (bool)$act->profile,
            'image'       => $act->picture ? $act->picture->image : null,
        ];
    }

    public function includeProfile(Act $act): ?Item
    {
        return $act->profile ? $this->item($act->profile, new ActProfileTransformer()) : null;
    }
}
