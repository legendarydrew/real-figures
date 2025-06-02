<?php

namespace App\Transformers;

use App\Models\Song;
use League\Fractal\TransformerAbstract;

class GoldenBuzzerSongBreakdownTransformer extends TransformerAbstract
{

    public function transform(Song $row): array
    {
        return [
            'song'          => fractal($row, new SongAdminTransformer())->toArray(),
            'buzzer_count' => $row->goldenBuzzers->count(),
            'amount_raised' => $row->goldenBuzzers->sum('amount')
        ];
    }
}
