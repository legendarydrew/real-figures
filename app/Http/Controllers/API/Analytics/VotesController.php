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
 * VotesController
 * This returns analytics data for votes cast over the specified period.
 * We would be interested in:
 * - votes cast per day
 * - votes cast per hour
 *
 * @package App\Http\Controllers\API\Analytics
 */
class VotesController extends AnalyticsAPIController
{

    const string CACHE_KEY = 'votes';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'vote',
                ])
            ]),
        ]);

        return Analytics::get(
            Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['dateHour'], // date and hour.
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return AnalyticsChartFormatter::byHour($rows);
    }

}
