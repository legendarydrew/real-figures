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
 * PlaylistController
 * This returns analytics data for the use of playlist buttons (previous and next) over the specified period.
 */
class PlaylistController extends AnalyticsAPIController
{
    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'playlist',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['dateHour', 'customEvent:label'],
            maxResults: 1000,
            dimensionFilter: $filter
        );

    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $stacked_data = AnalyticsChartFormatter::stackedByTime(
            $rows,
            'customEvent:label'
        );

        // Fill in the gaps (dates).
        $this->fillTimeGaps($stacked_data, $days);

        $stacked_data['table'] = $rows->groupBy('customEvent:label')->map(fn($r) => [
            'button' => $r->first()['customEvent:label'],
            'count'  => $r->sum('eventCount'),
        ])->values();

        return $stacked_data;
    }
}
