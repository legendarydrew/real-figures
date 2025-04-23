<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\SongPlay;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('dashboard', [
            'song_plays' => fn() => $this->getPlaysThisWeek()
        ]);
    }

    /**
     * Returns data for the number of Song plays over the last week.
     *
     * @return array
     */
    protected function getPlaysThisWeek(): array
    {
        // Total Song plays for each day.
        $plays = SongPlay::where('played_on', '>', now()->subWeek())
                         ->select(['played_on', DB::raw('SUM(play_count) as play_count')])
                         ->orderBy('played_on')
                         ->groupBy('played_on')
                         ->get();

        return [
            'days' => $plays->map(fn($play) => [
                'date'       => $play->played_on->format('Y-m-d'),
                'play_count' => $play->play_count
            ])->toArray(),
        ];

    }
}
