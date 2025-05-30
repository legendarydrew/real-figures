<?php

namespace App\Transformers;

use App\Models\Round;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Primitive;
use League\Fractal\TransformerAbstract;

class RoundTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = ['songs'];

    protected array $availableIncludes = ['full_title'];

    public function transform(Round $round): array
    {

        return [
            'id'       => (int)$round->id,
            'title'    => $round->title,
            'deadline' => $round->ends_at->toISOString(),
        ];
    }

    public function includeSongs(Round $round): Collection
    {
        $round->loadMissing(['songs', 'songs.act']);
        return $this->collection($round->songs, new SongAdminTransformer(), '');
    }

    public function includeFullTitle(Round $round): Primitive
    {
        return $this->primitive($round->full_title);
    }
}
