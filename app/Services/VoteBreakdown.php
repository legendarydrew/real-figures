<?php

namespace App\Services;

use App\Models\Round;
use App\Transformers\RoundVoteBreakdownTransformer;

class VoteBreakdown
{

    public function forRound(Round $round): array
    {
        $round->loadMissing(['outcomes']);
        return fractal($round, new RoundVoteBreakdownTransformer())->toArray();
    }

}
