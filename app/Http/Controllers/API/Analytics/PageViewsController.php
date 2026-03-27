<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use App\Support\AnalyticsChartFormatter;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * PageViewsController
 * This returns analytics data for page views over the specified period.
 */
class PageViewsController extends AnalyticsAPIController
{
    public const string CACHE_KEY = 'page-views';

    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchTotalVisitorsAndPageViews(
            period: Period::days($days),
            maxResults: 1000
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return AnalyticsChartFormatter::byDate($rows, $days, ['screenPageViews', 'activeUsers']);
    }
}
