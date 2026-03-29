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
 * VoteChoicesController
 * This returns analytics data for how many Songs were chosen for each vote,
 * over the specified period.
 * Instead of messing around with SQL, the number of Songs chosen in a vote is recorded
 * with the vote event.
 */
class VoteChoicesController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'viewport';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'vote',
                ]),
            ]),
        ]);

        return Analytics::get(
            Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'customEvent:choices'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:choices',
        );

        $this->fillDateGaps($data, $days);

        $data['table'] = $rows->groupBy('customEvent:choices')->map(fn($r) => [
            'choices' => $r->first()['customEvent:choices'],
            'count'   => $r->sum('eventCount'),
        ])->sortBy('choices')->values();

        return $data;
    }
}
