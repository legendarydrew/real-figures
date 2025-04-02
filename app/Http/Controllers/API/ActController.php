<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

    public function show(int $act_id): JsonResponse
    {
        return fractal(Act::findOrFail($act_id), new ActTransformer())->includeProfile()->respond();
    }

    public function store(ActRequest $request): JsonResponse
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

    public function update(ActRequest $request, int $act_id): JsonResponse
    {
        $act  = Act::findOrFail($act_id);
        $data = $request->validated();

        DB::transaction(function () use ($act, $data)
        {
            $act->update([
                'name' => $data['name']
            ]);
            if (isset($data['profile']))
            {
                $act->profile()->updateOrCreate(['act_id' => $act->id], $data['profile']);
                // https://stackoverflow.com/a/62489173/4073160
            }
            else
            {
                $act->profile()->delete();
            }
        });

        return fractal($act, new ActTransformer())->respond();
    }

    public function destroy(int $act_id): JsonResponse
    {
        $act = Act::findOrFail($act_id);
        $act->delete();

        return response()->json(null, 204);
    }
}
