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
        return Inertia::render('back/stages', [
            'stages' => fn() => fractal(Stage::all())->transformWith(StageAdminTransformer::class)->toArray(),
            'songs'  => fn() => fractal(Song::withAggregate('act', 'name')
                                            ->orderBy('act_name')
                                            ->orderBy('title')
                                            ->get(), new SongAdminTransformer())->toArray()['data'],
            'roundConfig' => config('contest.rounds')
        ]);
    }
}
