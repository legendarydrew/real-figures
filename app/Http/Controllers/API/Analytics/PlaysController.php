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
 * PlaysController
 * This returns analytics data for the number of Song plays (through this site) over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class PlaysController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.plays'))
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

            $rows = Analytics::get(
                period: Period::days($days),
                metrics: ['eventCount'],
                dimensions: ['dateHour'],
                maxResults: 1000,
                dimensionFilter: $filter
            );

            \Cache::set('analytics.plays', $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = AnalyticsChartFormatter::byHour($rows);

        return response()->json($data);
    }
}
