<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Transformers\RoundAdminTransformer;
use Inertia\Inertia;
use Inertia\Response;

/**
 * StageRoundsController
 * This endpoint will be used to fetch information about a Stage's Rounds.
 *
 * @package App\Http\Controllers\API
 */
class StageRoundsController extends Controller
{

    public function show(int $stage_id): Response
    {
        $stage = Stage::findOrFail($stage_id);

        return Inertia::render('back/stages', [
            'rounds' => fn() => fractal($stage->rounds, new RoundAdminTransformer())->toArray()['data']
        ]);
    }
}
