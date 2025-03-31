<?php

namespace App\Http\Controllers;

use App\Models\Act;
use App\Transformers\ActTransformer;
use Spatie\Fractal\Fractal;

class ActController extends Controller
{
    public function index(): Fractal
    {
        return fractal(Act::paginate(), new ActTransformer());
    }

    public function show(int $act_id)
    {
        // to be implemented.
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
