<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Stage;
use App\Transformers\SongAdminTransformer;
use App\Transformers\StageAdminTransformer;
use Inertia\Inertia;
use Inertia\Response;

class StagesController extends Controller
{
    public function index(): Response
    {
        $stages = Stage::with(['winners.round', 'winners.song.act', 'rounds' => fn($q) => $q->withCount('votes')])
                       ->get();
        $songs  = Song::with(['act', 'language', 'urls', 'act.languages'])
                      ->withAggregate('act', 'name')
                      ->orderBy('act_name')
                      ->orderBy('title')
                      ->get();

        return Inertia::render('back/stages-page', [
            'stages'      => fn() => fractal($stages)->transformWith(StageAdminTransformer::class)->toArray(),
            'songs'       => fn() => fractal($songs, new SongAdminTransformer)->toArray(),
            'roundConfig' => config('contest.rounds'),
        ]);
    }
}
