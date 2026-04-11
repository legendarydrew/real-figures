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
 */
class ActViewsController extends AnalyticsAPIController
{
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
                            ]),
                        ]),
                    ]),
                    new FilterExpression([
                        'filter' => new Filter([
                            'field_name'    => 'customEvent:type',
                            'string_filter' => new StringFilter([
                                'match_type' => Filter\StringFilter\MatchType::EXACT,
                                'value'      => 'act',
                            ]),
                        ]),
                    ]),
                ],
            ]),
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
            'customEvent:act',
            top: 10
        );

        $this->fillDateGaps($data, $days);

        // Prefetch Acts (to prevent N+1 queries).
        $acts          = Act::whereIn('slug', $data['keys'])->with(['profile'])->get();
        $data['table'] = array_map(fn($slug) => [
            'act'   => $slug !== 'Other' ?
                fractal($acts->first(fn(Act $act) => $act->slug === $slug), ActTransformer::class) :
                null,
            'count' => collect($data['data'])->pluck($slug)->sum(),
        ], $data['keys']);

        // Sort the table results in descending count order.
        usort($data['table'], function ($a, $b)
        {
            if (is_null($a['act']))
            {
                return 1;
            }
            elseif (is_null($b['act']))
            {
                return -1;
            }

            return $a['count'] > $b['count'] ? -1 : 1;
        });

        return $data;
    }
}
