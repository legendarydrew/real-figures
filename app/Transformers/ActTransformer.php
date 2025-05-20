<?php

namespace App\Transformers;

use App\Models\Act;
use Illuminate\Support\Str;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class ActTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['profile', 'profileContent'];

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

    /**
     * Include an Act's profile for display on the main site.
     *
     * @param Act $act
     * @return Primitive|null
     */
    public function includeProfileContent(Act $act): ?Primitive
    {
        if ($act->profile)
        {
            return $this->primitive([
                'description' => Str::markdown($act->profile->description),
            ]);
        }
        return null;
    }
}
