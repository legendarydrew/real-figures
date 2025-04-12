<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Transformers\StageAdminTransformer;
use Inertia\Inertia;
use Inertia\Response;

class StagesController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('back/stages', [
            'stages' => fractal(Stage::all())->transformWith(StageAdminTransformer::class)->toArray()
        ]);
    }
}
