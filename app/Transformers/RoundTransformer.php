<?php

namespace App\Transformers;

use App\Models\Round;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class RoundTransformer extends TransformerAbstract
{
    protected array $defaultIncludes = ['songs'];

    public function transform(Round $round): array
    {
        $round->loadMissing(['songs', 'songs.act']);

        return [
            'id'       => (int)$round->id,
            'title'    => $round->title,
            'deadline' => $round->ends_at->toISOString(),
        ];
    }

    public function includeSongs(Round $round): Collection
    {
        return $this->collection($round->songs, new SongAdminTransformer(), '');
    }
}
