<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * BrowsersController
 * This returns analytics data for browsers used to access the site over the specified period.
 */
class BrowsersController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'browsers';

    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchTopBrowsers(
            period: Period::days($days),
            maxResults: 100
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return $rows->toArray();
    }
}
