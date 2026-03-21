<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use App\Support\AnalyticsChartFormatter;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * DonationsController
 * This returns analytics data for anonymous versus not-anonymous donations over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class DonationsAnonymousController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'donations_anonymous';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'donation',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'customEvent:anonymous'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        // Group the results by anonymous/not anonymous.
        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:anonymous'
        );

        $data['table'] = $rows->groupBy('customEvent:anonymous')->map(fn($r, $key) => [
            'name'  => $key ? 'Not anonymous' : 'Anonymous',
            'count' => $r->sum('eventCount'),
        ])->values();

        return $data;
    }
}
