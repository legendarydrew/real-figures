<?php

namespace App\Http\Controllers\API;

use App\Facades\RoundAllocateFacade;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoundAllocateRequest;
use App\Models\Song;
use App\Models\Stage;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class StageAllocateController extends Controller
{

    public function store(RoundAllocateRequest $request, int $stage_id): RedirectResponse
    {
        $stage    = Stage::findOrFail($stage_id);
        $data     = $request->validated();
        $songs    = Song::whereIn('id', $data['song_ids'])->get();
        $start_at = isset($data['start_at']) ? Carbon::parse($data['start_at'])->utc() : null;

        RoundAllocateFacade::songs($stage, $songs, songs_per_round: $data['per_round'], round_start: $start_at, round_duration: "{$data['duration']} days");

        return to_route('admin.stages');
    }
}
