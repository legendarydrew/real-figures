<?php

namespace App\Transformers;

use App\Models\Song;
use League\Fractal\TransformerAbstract;

class SongAdminTransformer extends TransformerAbstract
{

    public function transform(Song $song): array
    {
        return [
            'id'         => (int)$song->id,
            'title'      => $song->title,
            'act_id'     => (int)$song->act_id,
            'act'        => [
                'name' => $song->act->name
            ],
            'play_count' => (int)$song->play_count,
            // TODO perhaps add the song language.
        ];
    }
}
