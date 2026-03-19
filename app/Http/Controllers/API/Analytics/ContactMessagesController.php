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
 * ContactMessagesController
 * This returns analytics data for messages sent through the Contact page for the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class ContactMessagesController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'contact';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'contact_sent',
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
