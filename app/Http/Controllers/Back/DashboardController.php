<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\RoundVote;
use App\Models\SongPlay;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{

    public function index(): Response
    {
        return Inertia::render('dashboard', [
            'song_plays' => fn() => $this->getPlaysThisWeek(),
            'votes'      => fn() => $this->getVotesThisWeek()
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
        $total_plays = SongPlay::where('played_on', '>', now()->subWeek())
                               ->select(['played_on', DB::raw('SUM(play_count) as play_count')])
                               ->orderBy('played_on')
                               ->groupBy('played_on')
                               ->get();

        // Songs played in the last day.
        $song_plays = SongPlay::where('played_on', '>', now()->subDay())
                              ->select(['song_id', 'played_on', DB::raw('SUM(play_count) as play_count')])
                              ->orderByDesc('play_count')
                              ->groupBy('song_id')
                              ->take(6)
                              ->get();

        return [
            'days'  => $total_plays->map(fn($play) => [
                'date'       => $play->played_on->format('Y-m-d'),
                'play_count' => $play->play_count
            ])->toArray(),
            'songs' => $song_plays->map(fn($play) => [
                'title'      => $play->song->full_title,
                'play_count' => $play->play_count
            ])
        ];
    }

    /**
     * Returns data for the number of votes cast over the last week.
     *
     * @return array
     */
    protected function getVotesThisWeek(): array
    {
        $vote_counts = RoundVote::where('created_at', '>', now()->subWeek())
                                ->select([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as votes')])
                                ->groupBy('date')
                                ->get()
                                ->toArray();

        // Fill in the blanks!
        $date   = now()->subWeek();
        $output = [];
        while ($date < now())
        {
            $day_date     = $date->format('Y-m-d');
            $matching_row = array_filter($vote_counts, fn($vote) => $vote['date'] === $day_date);
            $output[]     = [
                'date'  => $day_date,
                'votes' => $matching_row ? reset($matching_row)['votes'] : 0
            ];
            $date         = $date->addDay();
        }
        return $output;
    }
}
