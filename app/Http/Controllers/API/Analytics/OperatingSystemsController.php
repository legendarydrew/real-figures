<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * OperatingSystemsController
 * This returns analytics data for operating systems used to access the site the specified period.
 */
class OperatingSystemsController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchTopOperatingSystems(
            period: Period::days($days),
            maxResults: 100
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return $rows->toArray();
    }
}
