<?php

namespace App\Http\Controllers\Back;

use App\Enums\ContestStatus;
use App\Facades\ContestFacade;
use App\Facades\ContestFacade as Contest;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\GoldenBuzzer;
use App\Models\Round;
use App\Models\RoundVote;
use App\Models\Song;
use App\Models\SongPlay;
use App\Models\Subscriber;
use App\Support\AnalyticsChartFormatter;
use App\Transformers\ActTransformer;
use Illuminate\Support\Carbon;
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

        return Inertia::render('back/dashboard-page', [
            'contest_status' => fn () => $this->getContestStatus(),
            'analytics_views' => fn () => $this->getPageViews(),
            'donations' => fn () => [
                'golden_buzzers' => GoldenBuzzer::count(),
                'count' => Donation::count(),
            ],
            'buzzer_count' => fn () => GoldenBuzzer::count(),
            'message_count' => fn () => ContactMessage::whereNull('read_at')->count(),
            'song_plays' => fn () => $this->getPlaysThisWeek(),
            'subscriber_count' => fn () => Subscriber::confirmed()->count(),
            'votes' => fn () => $this->getVotesThisWeek(),
            'vote_count' => fn () => $current_stage ? $current_stage->vote_count : 0,
        ]);
    }

    /**
     * Returns data for the number of Song plays over the last week.
     */
    protected function getPlaysThisWeek(): array
    {
        // Total Song plays for each day.
        $total_plays = SongPlay::where('played_on', '>', now()->subWeek())
            ->select(['played_on AS date', DB::raw('SUM(play_count) as play_count')])
            ->orderBy('date')
            ->groupBy('date')
            ->get()
            ->map(fn ($row) => ['date' => Carbon::parse($row->date), 'play_count' => $row->play_count]);

        // Songs played in the last day.
        $song_plays = SongPlay::where('played_on', '>', now()->subDay())
            ->select(['song_id', 'played_on AS date', DB::raw('SUM(play_count) as play_count')])
            ->orderByDesc('play_count')
            ->groupBy('song_id', 'date', 'play_count')
            ->take(6)
            ->get();

        return [
            'days' => AnalyticsChartFormatter::byDate($total_plays, 7, ['play_count']),
            'songs' => $song_plays->map(fn ($play) => [
                'title' => $play->song->full_title,
                'play_count' => $play->play_count,
            ]),
        ];
    }

    /**
     * Returns data for the number of votes cast over the last week.
     */
    protected function getVotesThisWeek(): array
    {
        $vote_counts = RoundVote::where('created_at', '>', now()->subWeek())
            ->select([DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as votes')])
            ->groupBy('date')
            ->get();
        $rows = $vote_counts->map(fn ($row) => ['date' => Carbon::parse($row['date']), 'votes' => $row['votes']]);

        return AnalyticsChartFormatter::byDate($rows, 7, ['votes']);
    }

    protected function getPageViews(): array
    {
        $analyticsData = Analytics::fetchTotalVisitorsAndPageViews(Period::days(14));
        $rows = $analyticsData->map(fn ($row) => [
            'date' => $row['date'],
            'views' => $row['screenPageViews'],
            'visitors' => $row['activeUsers'],
        ]);

        return AnalyticsChartFormatter::byDate($rows, 14, ['views', 'visitors']);
    }

    /**
     * Returns the current state of the Contest.
     */
    protected function getContestStatus(): array
    {
        $current_stage = Contest::getCurrentStage();
        $output = [
            'status' => ContestStatus::COMING_SOON,
        ];

        if (Contest::isOver()) {
            $output['status'] = ContestStatus::OVER;
        } else {
            if ($current_stage?->hasEnded()) {
                $output = [
                    'status' => ContestStatus::JUDGEMENT,
                    'round' => $current_stage->title,
                ];
            } else {
                $current_round = $current_stage?->rounds->first(fn (Round $round) => $round->isActive());
                if ($current_round) {
                    $acts = $current_round->songs->map(fn (Song $song) => $song->act);
                    $output = [
                        'status' => ContestStatus::ACTIVE,
                        'round' => $current_round->full_title,
                        'countdown' => $current_round->ends_at->toISOString(),
                        'acts' => fractal($acts, ActTransformer::class)->toArray(),
                    ];
                } else {
                    $current_round = $current_stage?->rounds->first();
                    if ($current_round) {
                        $output = [
                            'status' => ContestStatus::COUNTDOWN,
                            'round' => $current_round->full_title,
                            'countdown' => $current_round->starts_at->toISOString(),
                        ];
                    }
                }
            }

        }

        return $output;
    }
}
