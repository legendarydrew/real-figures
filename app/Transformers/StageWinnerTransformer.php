<?php

namespace App\Transformers;

use App\Models\Stage;
use App\Models\StageWinner;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class StageWinnerTransformer extends TransformerAbstract
{

    public function transform(StageWinner $winner): array
    {
        return [
            'id'        => $winner->id,
            'round'     => $winner->round->title,
            'song'      => [
                'title' => $winner->song->title,
                'act'   => fractal($winner->song->act, new ActTransformer()),
            ],
            'is_winner' => (bool)$winner->is_winner,
        ];
    }

    public function includeRounds(Stage $stage): Primitive
    {
        $rounds = fractal($stage->rounds)->parseIncludes(['songs'])->transformWith(new RoundAdminTransformer())->toArray();
        return $this->primitive($rounds);
    }

}
