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
 * OutboundController
 * This returns analytics data about navigations to external sites.
 * Enhanced measurement events must be enabled to record them like this.
 * https://support.google.com/analytics/answer/9216061?sjid=10952981861936825735-EU#enable_disable
 *
 * @package App\Http\Controllers\API\Analytics
 */
class OutboundController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'outbound';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name'    => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value'      => 'click',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'linkUrl'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $stacked_data = AnalyticsChartFormatter::stackedByDate($rows, 'linkUrl');

        $data   = [];
        $end    = now();
        $cursor = $end->copy()->subDays($days);
        while ($cursor->lte($end))
        {
            $current_date = $cursor->format('Y-m-d');
            $matching_row = array_find($stacked_data['data'], fn($row) => $row['date'] === $current_date);
            if ($matching_row)
            {
                $data[$current_date] = [...$matching_row];
            }
            else
            {
                $data[$current_date] = ['date' => $current_date];
                foreach ($stacked_data['keys'] as $key)
                {
                    $data[$current_date][$key] = 0;
                }
            }
            $cursor->addDay();
        }
        ksort($data); // sort by ascending date.

        $stacked_data['data'] = array_values($data);

        $stacked_data['table'] = $rows->groupBy('linkUrl')->map(fn($r) => [
            'url'   => $r->first()['linkUrl'],
            'count' => $r->sum('eventCount'),
        ])->sortByDesc('count')->values();

        return $stacked_data;
    }

}
