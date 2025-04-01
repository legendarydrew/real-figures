<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Transformers\ActTransformer;
use Illuminate\Http\JsonResponse;
use Spatie\Fractal\Fractal;

class ActController extends Controller
{
    public function index(): JsonResponse
    {
        return fractal(Act::paginate(), new ActTransformer())->respond();
    }

    public function show(int $act_id)
    {
        $act = Act::findOrFail($act_id);
        return fractal($act, new ActTransformer())->includeProfile()->respond();
    }

    public function create()
    {
        // to be implemented.
    }

    public function update(int $act_id)
    {
        // to be implemented.
    }

    public function destroy(int $act_id)
    {
        // to be implemented.
    }
}
