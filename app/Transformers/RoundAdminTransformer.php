<?php

namespace App\Transformers;

use App\Enums\VoteType;
use App\Models\Round;
use App\Models\RoundVote;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class RoundAdminTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['songs'];

    public function transform(Round $round): array
    {
        $votes = $round->votes;

        return [
            'id'         => (int)$round->id,
            'title'      => $round->title,
            'starts_at'  => $round->starts_at->format('F d Y H:i'),
            'ends_at'    => $round->ends_at->format('F d Y H:i'),
            'vote_count' => [
                'total'    => $votes->count(),
                'public'   => $votes->filter(fn(RoundVote $vote) => $vote->vote_type === VoteType::ORGANIC->value)->count(),
                'manual'   => $votes->filter(fn(RoundVote $vote) => $vote->vote_type === VoteType::MANUAL->value)->count(),
                'dumbrick' => $votes->filter(fn(RoundVote $vote) => $vote->vote_type === VoteType::DUMBRICK->value)->count(),
            ],
        ];
    }

    public function includeSongs(Round $round): Collection
    {
        // One way of getting around the "data" property for nested collections.
        return $this->collection($round->songs, new SongAdminTransformer, '');
    }
}
