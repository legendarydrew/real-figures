<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Transformers\ActTransformer;
use Inertia\Inertia;
use Inertia\Response;

/**
 * ActsController
 * A page that displays information about each Act.
 *
 * @package App\Http\Controllers\Front
 */
class ActsController extends Controller
{
    public function index(): Response
    {
        $acts = Act::whereHas('songs')->get();
        if ($acts->isNotEmpty())
        {
            return Inertia::render('front/acts', [
                'acts' => fn() => fractal($acts->sortBy('name'), new ActTransformer(), '')->toArray()
            ]);
        }
        abort(404);
    }

    public function show(string $slug): Response
    {
        $act = Act::whereSlug($slug)->whereHas('profile')->first();

        return Inertia::render('front/acts', [
            'acts'       => fn() => fractal(Act::whereHas('songs')->orderBy('name')->get(), new ActTransformer(), '')->toArray(),
            'currentAct' => fn() => fractal($act, new ActTransformer(), '')->parseIncludes(['profileContent'])->toArray()
        ]);
    }
}
