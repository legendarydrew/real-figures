<?php

namespace App\Transformers;

use App\Models\Round;
use App\Models\RoundOutcome;
use League\Fractal\TransformerAbstract;

class RoundVoteBreakdownTransformer extends TransformerAbstract
{

    public function transform(Round $round): array
    {
        return [
            'id'         => (int)$round->id,
            'title'      => $round->full_title,
            'vote_count' => $round->votes()->count(),
            'songs'      => $round->outcomes->map(fn(RoundOutcome $outcome) => [
                'song'                => fractal($outcome->song, new SongTransformer()),
                'score'               => $outcome->score,
                'first_choice_votes'  => $outcome->first_votes,
                'second_choice_votes' => $outcome->second_votes,
                'third_choice_votes'  => $outcome->third_votes
            ])
        ];
    }

}
