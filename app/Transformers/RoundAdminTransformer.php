<?php

namespace App\Transformers;

use App\Models\Round;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class RoundAdminTransformer extends TransformerAbstract
{
    protected array $availableIncludes = ['songs'];

    public function transform(Round $round): array
    {
        return [
            'id'        => (int)$round->id,
            'title'     => $round->title,
            'starts_at' => $round->starts_at->format('F d Y H:i'),
            'ends_at'   => $round->ends_at->format('F d Y H:i'),
        ];
    }

    public function includeSongs(Round $round): Collection
    {
        // One way of getting around the "data" property for nested collections.
        return $this->collection($round->songs, new SongAdminTransformer(), '');
    }
}
