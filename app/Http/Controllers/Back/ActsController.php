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
        $acts = fractal(Act::paginate())->transformWith(ActTransformer::class)->toArray();
        return Inertia::render('back/acts', [
            'acts' => $acts
        ]);
    }
}
