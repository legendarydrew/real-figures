<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Song;
use Illuminate\Http\RedirectResponse;

class SongController extends Controller
{

    public function store(SongRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Song::factory()->create($data);

        return to_route('admin.songs');
    }

    public function update(SongRequest $request, int $song_id): RedirectResponse
    {
        $data = $request->validated();
        Song::findOrFail($song_id)->update($data);

        return to_route('admin.songs');
    }

    public function destroy(int $song_id): RedirectResponse
    {
        Song::findOrFail($song_id)->delete();

        return to_route('admin.songs');
    }
}
