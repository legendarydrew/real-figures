<?php

namespace App\Http\Controllers\API;

use App\Facades\RoundAllocateFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoundAllocateRequest;
use App\Models\Song;
use App\Models\Stage;
use App\Transformers\SongAdminTransformer;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StageAllocateController extends Controller
{

    public function store(RoundAllocateRequest $request, int $stage_id): RedirectResponse
    {
        $stage    = Stage::findOrFail($stage_id);
        $data     = $request->validated();
        $songs    = Song::whereIn('id', $data['song_ids'])->get();
        $start_at = Carbon::parse($data['start_at']);

        RoundAllocateFacade::songs($stage, $songs, songs_per_round: $data['per_round'], round_start: $start_at, duration: "{$data['duration']} days");

        return to_route('admin.stages');
    }
}
