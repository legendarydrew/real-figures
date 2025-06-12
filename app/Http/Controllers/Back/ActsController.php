<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Transformers\ActTransformer;
use Inertia\Inertia;
use Inertia\Response;

class ActsController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('back/acts', [
            'acts' => fn() => fractal(Act::with(['profile', 'picture'])->paginate(12))
                ->transformWith(ActTransformer::class)
                ->withResourceName('data')
                ->toArray()
            // "lazy loading" of data by using a callback.
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('back/act-edit');
    }

    public function edit(int $id): Response
    {
        $act = Act::findOrFail($id);
        return Inertia::render('back/act-edit', [
            'act' => fn() => fractal($act, new ActTransformer())->parseIncludes(['meta', 'profile', 'picture']),
        ]);
    }
}
