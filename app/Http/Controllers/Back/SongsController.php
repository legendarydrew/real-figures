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
            // https://stackoverflow.com/questions/38261546/order-by-relationship-column#38262311
                Song::join('acts', 'act_id', '=', 'acts.id')
                    ->orderBy(...$sort)->paginate()
            )->transformWith(SongAdminTransformer::class)->toArray()
        ]);
    }
}
