<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use App\Models\Act;
use App\Support\AnalyticsChartFormatter;
use App\Transformers\ActTransformer;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * ActViewsController
 * This returns analytics data for Act profiles viewed over the specified period.
 * Of course, this would (and should) only apply to Acts that have profiles.
 * We would be interested in:
 * - viewed Acts per day
 * - which Acts were viewed, and how many times.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class ActViewsController extends AnalyticsAPIController
{

    const string CACHE_KEY = 'acts-viewed';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'and_group' => new FilterExpressionList([
                'expressions' => [
                    new FilterExpression([
                        'filter' => new Filter([
                            'field_name'    => 'eventName',
                            'string_filter' => new Filter\StringFilter([
                                'match_type' => Filter\StringFilter\MatchType::EXACT,
                                'value'      => 'dialog_open',
                            ])
                        ]),
                    ]),
                    new FilterExpression([
                        'filter' => new Filter([
                            'field_name'    => 'customEvent:type',
                            'string_filter' => new StringFilter([
                                'match_type' => Filter\StringFilter\MatchType::EXACT,
                                'value'      => 'act',
                            ])
                        ]),
                    ]),
                ],
            ])
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'customEvent:act'],
            maxResults: 1000,
            dimensionFilter: $filter,
            keepEmptyRows: true

        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:act'
        );

        $data['table'] = $rows?->groupBy('customEvent:act')->map(fn($r) => [
            'act'   => fractal(Act::whereSlug($r->first()['customEvent:act'])->first(), ActTransformer::class),
            'count' => $r->sum('eventCount'),
        ])->sortByDesc('count')->values() ?? [];

        return $data;
    }
}
