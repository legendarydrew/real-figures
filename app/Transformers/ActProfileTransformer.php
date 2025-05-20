<?php

namespace App\Transformers;

use App\Models\ActProfile;
use League\Fractal\TransformerAbstract;

class ActProfileTransformer extends TransformerAbstract
{

    public function transform(ActProfile $act_profile): array
    {
        return [
            'description' => $act_profile->description
        ];
    }
}
