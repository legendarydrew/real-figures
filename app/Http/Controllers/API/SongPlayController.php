<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\JsonResponse;

class SongPlayController extends Controller
{
    /**
     * Simply increment the number of plays on the current day for the specified Song.
     */
    public function update(int $song_id): JsonResponse
    {
        $song = Song::findOrFail($song_id);
        $play = $song->plays()->firstOrCreate([
            'played_on' => now()->format('Y-m-d 00:00:00'),
            // manually adding the time is important!
        ]);
        $play->increment('play_count');
        $play->save();

        return response()->json(null, 204);
    }
}
