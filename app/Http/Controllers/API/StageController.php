<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageRequest;
use App\Models\Stage;
use App\Transformers\StageTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class StageController extends Controller
{
    public function index(): JsonResponse
    {
        return fractal(Stage::paginate(), new StageTransformer())->respond();
    }

    public function show(int $stage_id): JsonResponse
    {
        return fractal(Stage::findOrFail($stage_id), new StageTransformer())->respond();
    }

    public function store(StageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Stage::factory()->create($data);

        return to_route('admin.stages');
        // This would go back to the stages admin page, automatically updating the list of stages.
        // Validation errors are taken care of.
    }

    public function update(StageRequest $request, int $stage_id): RedirectResponse
    {
        $data = $request->validated();
        Stage::findOrFail($stage_id)->update($data);

        return to_route('admin.stages');
    }

    public function destroy(int $stage_id): RedirectResponse
    {
        Stage::findOrFail($stage_id)->delete();

        return to_route('admin.stages');
    }
}
