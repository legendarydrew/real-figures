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
 * SubscribersController
 * This returns analytics data for subscriber additions and removals over the specified period.
 * To keep things simple (because I was lazy), the net difference in subscribers is returned.
 */
class SubscribersController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'subscriber',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventValue'],
            dimensions: ['date'],
            maxResults: 1000,
            dimensionFilter: $filter,
            keepEmptyRows: true
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return AnalyticsChartFormatter::byDate($rows, $days, ['eventValue']);
    }
}
