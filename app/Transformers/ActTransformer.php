<?php

namespace App\Transformers;

use App\Models\Act;
use League\Fractal\TransformerAbstract;

class ActTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Act $act): array
    {
        return [
            'name'        => $act->name,
            'slug'        => $act->slug,
            'has_profile' => (bool)$act->profile
        ];
    }
}
