<?php

namespace App\Transformers;

use App\Models\Stage;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class CurrentStageTransformer extends TransformerAbstract
{

    public function transform(Stage $stage): array
    {
        return [
            'id'                  => (int)$stage->id,
            'title'               => $stage->title,
            'golden_buzzer_perks' => Str::markdown($stage->golden_buzzer_perks),
            'status'              => [
                'text' => $stage->status
            ],
        ];
    }

}
