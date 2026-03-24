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
 * SongPlaysController
 * This returns analytics data for the number of Song plays (through this site) over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class SongPlaysController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'plays';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'song_play',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['dateHour'],
            maxResults: 1000,
            dimensionFilter: $filter
        );

    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        return AnalyticsChartFormatter::byHour($rows);
    }

}
