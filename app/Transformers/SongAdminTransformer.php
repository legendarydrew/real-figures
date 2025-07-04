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
            'language'   => $song->language->code,
            'act_id'     => (int)$song->act_id,
            'act'        => [
                'name'  => $song->act->name,
                'image' => $song->act->image
            ],
            'play_count' => (int)$song->play_count,
            'url'        => $song->url ? $song->url->url : null,
            'video_id'   => $song->url ? $song->url->video_id : null,
        ];
    }
}
