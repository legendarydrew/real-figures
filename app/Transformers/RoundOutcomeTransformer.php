<?php

namespace App\Transformers;

use App\Models\RoundOutcome;
use League\Fractal\TransformerAbstract;

class RoundOutcomeTransformer extends TransformerAbstract
{
    public function transform(RoundOutcome $outcome): array
    {
        $outcome->loadMissing(['song', 'song.act']);

        return [
            'song' => fractal($outcome->song, SongTransformer::class, '')->toArray(),
            'score' => $outcome->score,
            'first_votes' => $outcome->first_votes,
            'second_votes' => $outcome->second_votes,
            'third_votes' => $outcome->third_votes,
        ];
    }
}
