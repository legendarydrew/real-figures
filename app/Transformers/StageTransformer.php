<?php

namespace App\Transformers;

use App\Models\Stage;
use Illuminate\Support\Str;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class StageTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['description', 'goldenBuzzerPerks'];

    public function transform(Stage $stage): array
    {
        return [
            'id'          => (int)$stage->id,
            'title'       => $stage->title,
            'description' => $stage->description,
            'status'      => $stage->status,
        ];
    }

    /**
     * Using this include will give us the HTML for the Stage description.
     *
     * @param Stage $stage
     * @return Primitive
     */
    public function includeDescription(Stage $stage): Primitive
    {
        return $this->primitive(Str::markdown($stage->description));
    }

    public function includeGoldenBuzzerPerks(Stage $stage): Primitive
    {
        return $this->primitive(Str::markdown($stage->golden_buzzer_perks));
    }

}
