<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SongRequest;
use App\Models\Language;
use App\Models\Song;
use App\Models\SongUrl;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Http\RedirectResponse;

class SongController extends Controller
{
    public function store(SongRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $song = Song::factory()->create([
            'title'       => $data['title'],
            'act_id'      => $data['act_id'],
            'language_id' => Language::whereCode($data['language'])->first()->id,
        ]);

        if (!empty($data['urls']))
        {
            SongUrl::factory(count($data['urls']))->for($song)->create([
                'url' => new Sequence(...array_map(fn($row) => $row['url'], $data['urls']))
            ]);
        }

        return to_route('admin.songs');
    }

    public function update(SongRequest $request, int $song_id): RedirectResponse
    {
        $data                = $request->validated();
        $data['language_id'] = Language::whereCode($data['language'])->first()->id;
        Song::findOrFail($song_id)->update($data);

        // Update any Song URLs, preserving IDs.
        $urls = $data['urls'] ?? [];
        $existing_ids = array_filter(array_map(fn($row) => $row['id'] ?? false, $urls));
        if (count($existing_ids))
        {
            SongUrl::whereSongId($song_id)->whereNotIn('id', $existing_ids)->delete();
        }
        else
        {
            SongUrl::whereSongId($song_id)->delete();
        }

        foreach ($urls as $row)
        {
            SongUrl::updateOrCreate([...$row, 'song_id' => $song_id]);
        }

        return to_route('admin.songs');
    }

    public function destroy(int $song_id): RedirectResponse
    {
        Song::findOrFail($song_id)->delete();

        return to_route('admin.songs');
    }
}
