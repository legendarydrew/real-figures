<?php

namespace App\Transformers;

use App\Models\Stage;
use League\Fractal\TransformerAbstract;

class StageTransformer extends TransformerAbstract
{

    public function transform(Stage $stage): array
    {
        return [
            'id'          => (int)$stage->id,
            'title'       => $stage->title,
            'description' => $stage->description,
            'status' => $stage->status,
        ];
    }
}
