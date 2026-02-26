<?php

namespace App\Transformers;

use App\Facades\VoteBreakdownFacade;
use App\Models\Round;
use App\Models\Stage;
use League\Fractal\TransformerAbstract;

class StageVoteBreakdownTransformer extends TransformerAbstract
{

    public function transform(Stage $stage): array
    {
        return [
            'id'         => $stage->id, // for testing purposes.
            'title'      => $stage->title,
            'breakdowns' => $stage->rounds->map(fn(Round $round) => VoteBreakdownFacade::forRound($round))
        ];
    }

}
