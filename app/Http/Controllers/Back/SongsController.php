<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Stage;
use App\Transformers\SongAdminTransformer;
use App\Transformers\StageAdminTransformer;
use Inertia\Inertia;
use Inertia\Response;

class SongsController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('back/songs', [
            'songs' => fractal(Song::paginate())->transformWith(SongAdminTransformer::class)->toArray()
        ]);
    }
}
