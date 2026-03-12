<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * DonationsController
 * This returns analytics data for donations started and completed over the specified period.
 * This one is trickier, because we would have to combine two reports.
 * We would be interested in:
 * - donations started per day
 * - donations completed per day
 *
 * @package App\Http\Controllers\API\Analytics
 */
class DonationsController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.donations.' . $days))
        {
            $rows = [];

            // https://developers.google.com/analytics/devguides/reporting/data/v1/basics#php_4
            $started_filter = new FilterExpression([
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
                                    'value'      => 'donate',
                                ])
                            ]),
                        ]),
                    ],
                ])
            ]);

            $rows['started'] = Analytics::get(
                Period::days($days),
                metrics: ['eventCount'],
                dimensions: ['date'],
                maxResults: 1000,
                dimensionFilter: $started_filter,
                keepEmptyRows: true
            );

            $completed_filter = new FilterExpression([
                'filter' => new Filter([
                    'field_name'    => 'eventName',
                    'string_filter' => new Filter\StringFilter([
                        'match_type' => Filter\StringFilter\MatchType::EXACT,
                        'value'      => 'donation',
                    ])
                ]),
            ]);

            $rows['completed'] = Analytics::get(
                Period::days($days),
                metrics: ['eventCount'],
                dimensions: ['date'],
                maxResults: 1000,
                dimensionFilter: $completed_filter,
                keepEmptyRows: true
            );

            \Cache::set('analytics.donations.' . $days, $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data   = [];
        $cursor = now()->subDays($days);
        do
        {
            $date = $cursor->format('Y-m-d');
            $data[$date] = ['date' => $date, 'started' => 0, 'completed' => 0];
            $cursor->addDay();
        }
        while ($cursor < now());

        $rows['started']->each(function ($row) use (&$data)
        {
            $data[$row['date']->format('Y-m-d')]['started'] = $row['eventCount'];
        });
        $rows['completed']->each(function ($row) use (&$data)
        {
            $data[$row['date']->format('Y-m-d')]['started'] = $row['eventCount'];
        });

        return response()->json(array_values($data));
    }
}
