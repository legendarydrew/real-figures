<?php

namespace App\Transformers;

use App\Models\Stage;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class StageAdminTransformer extends TransformerAbstract
{

    protected array $defaultIncludes = ['rounds'];

    public function transform(Stage $stage): array
    {
        return [
            'id'          => (int)$stage->id,
            'title'       => $stage->title,
            'description' => $stage->description,
            'status'      => [
                'has_started' => $stage->hasStarted(),
                'has_ended'   => $stage->hasEnded(),
            ],
        ];
    }

    public function includeRounds(Stage $stage): Primitive
    {
        $rounds = fractal($stage->rounds)->parseIncludes(['songs'])->transformWith(new RoundAdminTransformer())->toArray();
        return $this->primitive($rounds);
    }
}
