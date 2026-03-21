<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * PageViewsController
 * This returns analytics data for pages viewed over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class PagesController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.pages.' . $days))
        {
            $rows = Analytics::fetchMostVisitedPages(
                period: Period::days($days),
                maxResults: 12
            );

            \Cache::set('analytics.pages.' . $days, $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = $rows->map(fn($row) => [
            'title' => trim(explode('—', $row['pageTitle'])[0]),
            'url'   => $row['fullPageUrl'],
            'count' => $row['screenPageViews'],
        ]);

        // For a pie chart, create a list of top-level pages along with their view count.
        // Subpages will be grouped with their parent (eg. News and articles).
        $grouped = $data->map(fn($item) => [
            'url'   => implode('/', explode('/', $item['url'], 2)),
            'count' => $item['count']
        ])
                        ->groupBy(fn($item) => $item['url'])
                        ->map(fn($item, $key) => [
                            'url'   => $key,
                            'count' => $item->sum('count')
                        ]);

        return response()->json([
            'grouped' => $grouped->values(),
            'data'    => $data->values()
        ]);
    }
}
