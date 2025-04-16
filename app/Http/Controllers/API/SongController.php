<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Http\RedirectResponse;

class SongController extends Controller
{

    public function store(SongRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $song = Song::factory()->create($data);

        if (!empty($data['url']))
        {
            SongUrl::factory()->for($song)->create(['url' => $data['url']]);
        }

        return to_route('admin.songs');
    }

    public function update(SongRequest $request, int $song_id): RedirectResponse
    {
        $data = $request->validated();
        Song::findOrFail($song_id)->update($data);

        if (!empty($data['url']))
        {
            SongUrl::updateOrCreate(['song_id' => $song_id, 'url' => $data['url']]);
        }
        else
        {
            SongUrl::whereSongId($song_id)->delete();
        }

        return to_route('admin.songs');
    }

    public function destroy(int $song_id): RedirectResponse
    {
        Song::findOrFail($song_id)->delete();

        return to_route('admin.songs');
    }
}
