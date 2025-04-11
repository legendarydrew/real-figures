<?php

namespace App\Transformers;

use App\Models\Stage;
use League\Fractal\TransformerAbstract;

class StageAdminTransformer extends TransformerAbstract
{

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
            'rounds'      => []
        ];
    }
}
