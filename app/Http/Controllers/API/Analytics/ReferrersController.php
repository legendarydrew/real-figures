<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * ReferrersController
 * This returns analytics data about where Visitors came from, in terms of site links.
 */
class ReferrersController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        return Analytics::fetchTopReferrers(
            period: Period::days($days),
            maxResults: 1000
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        // Take the top x items, and group the others under 'Other'.
        $top   = $rows->take(12);
        $other = $rows->slice($top->count());

        $data = $top->map(fn($r) => [
            'referrer' => $r['pageReferrer'],
            'count'    => $r['screenPageViews'],
        ])->values();
        if ($other->isNotEmpty())
        {
            $data->add([
                'referrer' => 'Other',
                'count'    => $other->sum('screenPageViews'),
            ]);
        }

        return $data->toArray();
    }
}
