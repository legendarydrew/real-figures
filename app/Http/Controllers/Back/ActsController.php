<?php

namespace App\Http\Controllers\Back;

use App\Enums\ActRank;
use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Genre;
use App\Transformers\ActTransformer;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Inertia\Response;

class ActsController extends Controller
{
    public function index(): Response
    {
        // We want to display a measure of how many Acts have specific ranks.
        $ranks = Act::all()->map(fn(Act $act) => $act->rank_text);
        return Inertia::render('back/acts-page', [
            'acts'  => fn() => fractal(Act::with(['profile'])->paginate(20))
                ->transformWith(ActTransformer::class)
                ->withResourceName('data')
                ->toArray(),
            'ranks' => fn() => [
                'total' => $ranks->count(),
                'list'  => Lang::get('contest.act.rank'),
                'count' => array_count_values($ranks->toArray())
            ]
            // "lazy loading" of data by using a callback.
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('back/act-edit-page', [
            'genreList' => fn() => Genre::orderBy('name')->pluck('name')->toArray(),
        ]);
    }

    public function edit(int $id): Response
    {
        $act = Act::findOrFail($id);

        return Inertia::render('back/act-edit-page', [
            'act'       => fn() => fractal($act, new ActTransformer)->parseIncludes(['meta', 'profile']),
            'genreList' => fn() => Genre::orderBy('name')->pluck('name')->toArray(),
            'ranks'     => fn() => array_map(fn(ActRank $rank) => [
                'id'    => $rank->value,
                'label' => \Lang::get('contest.act.rank')[$rank->value]
            ],
                ActRank::cases()),
        ]);
    }
}
