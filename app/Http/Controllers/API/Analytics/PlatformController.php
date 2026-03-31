<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\OrderBy;
use Spatie\Analytics\Period;

/**
 * CountriesController
 * This returns analytics data for where the site was accessed over the specified period.
 */
class PlatformController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::get(
            period: Period::days($days),
            metrics: ['screenPageViews'],
            dimensions: ['platform'],
            maxResults: 1000,
            orderBy: [
                OrderBy::metric('screenPageViews', true),
            ]);
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return $rows->map(fn($row) => [
            'platform' => ucwords($row['platform']),
            'views'    => $row['screenPageViews'],
        ])->toArray();
    }
}
