<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActRequest;
use App\Models\Act;
use App\Models\ActProfile;
use App\Transformers\ActTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ActController extends Controller
{
    public function index(): JsonResponse
    {
        return fractal(Act::paginate(), new ActTransformer())->respond();
    }

    public function show(int $act_id)
    {
        return fractal(Act::findOrFail($act_id), new ActTransformer())->includeProfile()->respond();
    }

    public function store(ActRequest $request)
    {
        $data = $request->validated();
        $act  = null;
        DB::transaction(function () use (&$act, $data)
        {
            $act = Act::factory()->create([
                'name' => $data['name']
            ]);
            if (isset($data['profile']))
            {
                ActProfile::factory()->for($act)->create($data['profile']);
            }
        });

        return fractal($act, new ActTransformer())->respond(201);
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
