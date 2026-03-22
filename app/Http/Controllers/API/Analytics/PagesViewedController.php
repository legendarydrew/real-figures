<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * PagesViewedController
 * This returns analytics data for pages viewed over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class PagesViewedController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'pages_viewed';

    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchMostVisitedPages(
            period: Period::days($days),
            maxResults: 12
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $data = $rows->map(fn($row) => [
            'title' => trim(explode('—', $row['pageTitle'])[0]),
            'url'   => $row['fullPageUrl'],
            'count' => $row['screenPageViews'],
        ]);

        // For a pie chart, create a list of top-level pages along with their view count.
        // Subpages will be grouped with their parent (eg. News and articles).
        $grouped = $data->map(fn($item) => [
            'url'   => implode('/', array_slice(explode('/', $item['url']), 0, 2)),
            'count' => $item['count']
        ])
                        ->groupBy(fn($item) => $item['url'])
                        ->map(fn($item, $key) => [
                            'url'   => $key,
                            'count' => $item->sum('count')
                        ]);

        return [
            'grouped' => $grouped->values(),
            'data'    => $data->values()
        ];
    }
}
