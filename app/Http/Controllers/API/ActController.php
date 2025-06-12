<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActRequest;
use App\Models\Act;
use App\Models\ActPicture;
use App\Models\ActProfile;
use App\Transformers\ActTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ActController extends Controller
{
    public function index(): JsonResponse
    {
        return fractal(Act::paginate(), new ActTransformer())->withResourceName('data')->respond();
    }

    public function show(int $act_id): JsonResponse
    {
        return fractal(Act::findOrFail($act_id), new ActTransformer())->includeProfile()->respond();
    }

    public function store(ActRequest $request): RedirectResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($data)
        {
            $act = Act::factory()->create([
                'name'             => $data['name'],
                'is_fan_favourite' => $data['is_fan_favourite'],
            ]);
            if (isset($data['profile']))
            {
                ActProfile::factory()->for($act)->create($data['profile']);
            }
            if (!empty($data['image']))
            {
                ActPicture::updateOrCreate(['act_id' => $act->id], [
                    'image' => $data['image']
                ]);
            }
            else
            {
                $act->picture()->delete();
            }
        });

        if (isset($act))
        {
            return to_route('admin.acts.edit', ['id' => $act->id]);
        }
    }

    public function update(ActRequest $request, int $act_id): RedirectResponse
    {
        $act  = Act::findOrFail($act_id);
        $data = $request->validated();

        DB::transaction(function () use ($act, $data)
        {
            $act->update([
                'name'             => $data['name'],
                'is_fan_favourite' => $data['is_fan_favourite'],
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
            if (!empty($data['image']))
            {
                $act->picture()->updateOrCreate(['act_id' => $act->id], ['image' => $data['image']]);
            }
            else
            {
                $act->picture()->delete();
            }
        });

        return to_route('admin.acts.edit', ['id' => $act->id]);
    }

    public function destroy(int $act_id): RedirectResponse
    {
        Act::findOrFail($act_id)->delete();

        return to_route('admin.acts');
    }
}
