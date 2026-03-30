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
 * CollapseController
 * This returns analytics data for collapsible sections opened over the specified period.
 * These would suggest (but not necessarily mean) that specific content is being read.
 * We would be interested in:
 * - opened sections per day
 * - which sections were opened, and how many times.
 */
class CollapseController extends AnalyticsAPIController
{
    public const string CACHE_KEY = 'collapse';

    protected function analyticsQuery(int $days): Collection
    {
        // Building on ChatGPT's suggestion.

        // Filter results by the event name (in this case, we defined 'collapse_open').
        // Custom events have to be set up in Google Analytics:
        //    Admin → Custom definitions → Create custom dimension
        // e.g.
        //    Dimension name: Section ID
        //    Event parameter: section_id
        //    Scope: Event

        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value' => 'collapse_open',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'pageTitle', 'customEvent:section_id'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $stacked_data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:section_id'
        );

        // Fill in the gaps (dates).
        $this->fillDateGaps($stacked_data, $days);

        $stacked_data['table'] = $rows->groupBy('customEvent:section_id')->map(fn ($r) => [
            'page' => $r->first()['pageTitle'],
            'section' => $r->first()['customEvent:section_id'],
            'count' => $r->sum('eventCount'),
        ])->sortByDesc('count')->values();

        return $stacked_data;
    }
}
