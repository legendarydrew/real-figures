<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * CountriesController
 * This returns analytics data for where the site was accessed over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class BrowsersController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'countries';

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
