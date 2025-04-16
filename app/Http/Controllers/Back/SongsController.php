<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Song;
use App\Transformers\SongAdminTransformer;
use Inertia\Inertia;
use Inertia\Response;

class SongsController extends Controller
{

    public function index(): Response
    {
        // Sorting rows is done by passing a sort parameter, in the format "column:asc" or "column:desc".
        $sort = explode(':', request()->query('sort', 'title:asc'));

        return Inertia::render('back/songs', [
            'acts'  => fn() => Act::select(['id', 'name'])->orderBy('name')->get(),
            'songs' => fn() => fractal(
            // https://stackoverflow.com/a/72277299/4073160
                Song::withAggregate('act', 'name')
                    // creates an additional column called act_name, also prevents conflicting IDs.
                    ->orderBy(...$sort)->paginate()
            )->transformWith(SongAdminTransformer::class)->toArray()
        ]);
    }
}
