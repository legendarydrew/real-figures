<?php

namespace App\Http\Controllers\Back;

use App\Facades\ContestFacade;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Models\RoundVote;
use App\Models\SongPlay;
use App\Models\Subscriber;
use App\Transformers\DonationTransformer;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class DashboardController extends Controller
{

    public function index(): Response
    {
        $current_stage = ContestFacade::getCurrentStage();
        return Inertia::render('back/dashboard', [
            'analytics_countries' => fn() => $this->getTopCountries(),
            'analytics_pages'     => fn() => $this->getMostViewedPages(),
            'analytics_views'     => fn() => $this->getPageViews(),
            'donations'        => fn() => [
                'golden_buzzers' => GoldenBuzzer::count(),
                'rows'           => fractal(Donation::orderByDesc('id')->take(10)->get(), new DonationTransformer())->parseIncludes('amount')->toArray(),
                'count'          => Donation::count(),
                'total'          => sprintf("%s %s", config('contest.donation.currency'), number_format(Donation::sum('amount'), 2)),
                // making a dangerous assumption that the donations are all in the same currency.
            ],
            'buzzer_count'     => fn() => GoldenBuzzer::count(),
            'message_count'    => fn() => ContactMessage::whereNull('read_at')->count(),
            'song_plays'       => fn() => $this->getPlaysThisWeek(),
            'subscriber_count' => fn() => Subscriber::confirmed()->count(),
            'votes'            => fn() => $this->getVotesThisWeek(),
            'vote_count'       => fn() => $current_stage ? $current_stage->vote_count : 0
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
                               ->select([DB::raw('DATE(played_on) as date'), DB::raw('SUM(play_count) as play_count')])
                               ->orderBy('date')
                               ->groupBy('date')
                               ->get()
                               ->toArray();

        // Songs played in the last day.
        $song_plays = SongPlay::where('played_on', '>', now()->subDay())
                              ->select(['song_id', 'played_on', DB::raw('SUM(play_count) as play_count')])
                              ->orderByDesc('play_count')
                              ->groupBy('song_id', 'played_on', 'play_count')
                              ->take(6)
                              ->get();

        $dates              = $this->getDatesForLastWeek();
        $total_play_results = [];
        foreach ($dates as $day_date)
        {
            $matching_row         = array_filter($total_plays, fn($play) => $play['date'] === $day_date);
            $total_play_results[] = [
                'date'       => $day_date,
                'play_count' => $matching_row ? reset($matching_row)['play_count'] : 0
            ];
        }

        return [
            'days'  => $total_play_results,
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
        $dates  = $this->getDatesForLastWeek();
        $output = [];
        foreach ($dates as $day_date)
        {
            $matching_row = array_filter($vote_counts, fn($vote) => $vote['date'] === $day_date);
            $output[]     = [
                'date'  => $day_date,
                'votes' => $matching_row ? reset($matching_row)['votes'] : 0
            ];
        }
        return $output;
    }

    protected function getDatesForLastWeek(): array
    {
        $dates = [];
        $date  = now()->subWeek();
        while ($date < now())
        {
            $dates[] = $date->format('Y-m-d');
            $date    = $date->addDay();
        }
        return $dates;
    }

    protected function getPageViews(): array
    {
        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(14));
        $data          = $analyticsData->map(fn($row) => [
            'date'     => $row['date']->format('Y-m-d'),
            'views'    => $row['screenPageViews'],
            'visitors' => $row['activeUsers'],
        ])
                                       ->toArray();

        // For some reason we have to do the sorting here, as attempting to do it with the
        // collection returns nothing.
        usort($data, fn($a, $b) => strcmp($a['date'], $b['date']));

        return $data;
    }

    protected function getMostViewedPages(): array
    {
        $analyticsData = Analytics::fetchMostVisitedPages(Period::days(7));
        return $analyticsData->map(fn($row, $index) => [
            'index' => $index,
            'url'   => $row['fullPageUrl'],
            'title' => $row['pageTitle'],
            'views' => $row['screenPageViews'],
        ])->toArray();
    }

    protected function getTopCountries(): array
    {
        $analyticsData = Analytics::fetchTopCountries(Period::days(7));
        return $analyticsData->map(fn($row, $index) => [
            'index'   => $index,
            'country' => $row['country'],
            'views'   => $row['screenPageViews'],
        ])->toArray();
    }

}
