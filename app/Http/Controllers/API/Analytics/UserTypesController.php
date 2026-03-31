<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * UserTypesController
 * This returns analytics data for new vs. returning Visitors over the specified period.
 */
class UserTypesController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchUserTypes(
            period: Period::days($days)
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return $rows->map(fn($row) => [
            'type'  => ucfirst($row['newVsReturning']),
            'count' => $row['activeUsers'],
        ])->toArray();
    }
}
