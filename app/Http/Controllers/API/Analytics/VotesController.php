<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use App\Support\AnalyticsChartFormatter;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * VotesController
 * This returns analytics data for votes cast over the specified period.
 * We would be interested in:
 * - votes cast per day
 * - votes cast per hour
 *
 * @package App\Http\Controllers\API\Analytics
 */
class VotesController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.votes'))
        {
            $filter = new FilterExpression([
                'filter' => new Filter([
                    'field_name'    => 'eventName',
                    'string_filter' => new Filter\StringFilter([
                        'match_type' => Filter\StringFilter\MatchType::EXACT,
                        'value'      => 'vote',
                    ])
                ]),
            ]);

            $rows = Analytics::get(
                Period::days($days),
                metrics: ['eventCount'],
                dimensions: ['dateHour'], // date and hour.
                maxResults: 1000,
                dimensionFilter: $filter
            );

            \Cache::set('analytics.votes', $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = AnalyticsChartFormatter::byHour($rows);

        return response()->json($data);
    }
}
