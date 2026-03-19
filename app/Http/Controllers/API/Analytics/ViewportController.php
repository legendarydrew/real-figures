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
 * ViewportController
 * This returns analytics data for the viewport (not screen!) size the site was viewed in
 * over the specified period.
 * This requires a custom dimension to be set up, as well as JavaScript to record.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class ViewportController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'viewport';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'page_view',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['screenPageViews'],
            dimensions: ['date', 'customEvent:visitor_viewport'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:visitor_viewport',
            metric: 'screenPageViews',
        );

        $data['table'] = $rows->groupBy('customEvent:visitor_viewport')->map(fn($r) => [
            'viewport' => $r->first()['customEvent:visitor_viewport'],
            'views'    => $r->sum('screenPageViews'),
        ])->sortByDesc('screenPageViews')->values();

        return $data;
    }

}
