<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GoldenBuzzer;
use App\Models\Song;
use App\Transformers\GoldenBuzzerRoundBreakdownTransformer;
use App\Transformers\GoldenBuzzerSongBreakdownTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * GoldenBuzzerBreakdownController
 * This controller provides a breakdown of Golden Buzzer donations:
 * which Songs they were awarded to, at which stage, and the amounts raised in total.
 *
 * @package App\Http\Controllers\API
 */
class GoldenBuzzerBreakdownController extends Controller
{

    public function index(): JsonResponse
    {
        // Stage/Round totals.
        $round_results = GoldenBuzzer::with(['round', 'round.stage'])
                                     ->select(['round_id', 'amount'])
                                     ->addSelect(DB::raw('SUM(amount) as amount_raised'))
                                     ->groupBy('round_id')
                                     ->orderBy('round_id')
                                     ->get();

        // Song totals.
        // NOTE: for our implementation, the Songs used in each Stage will be different as they are
        // different versions. If Songs will be recycled for future Stages, it would be great to have
        // a breakdown of Golden Buzzers per Round for each Song.
        $song_results = Song::with(['goldenBuzzers'])
                            ->has('goldenBuzzers')
                            ->withCount('goldenBuzzers')
                            ->groupBy('id')
                            ->orderByDesc('golden_buzzers_count')
                            ->get();

        return response()->json([
            'rounds' => fractal($round_results, new GoldenBuzzerRoundBreakdownTransformer())->toArray(),
            'songs'  => fractal($song_results, new GoldenBuzzerSongBreakdownTransformer())->toArray()
        ]);
    }
}
