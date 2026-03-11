<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Support\AnalyticsChartFormatter;
use App\Transformers\ActTransformer;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * SongController
 * This returns analytics data for Songs played (through this site) over the specified period.
 * We would be interested in:
 * - Songs played per day
 * - which Songs were played, and how many times.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class SongController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.song-plays'))
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
                dimensions: ['date', 'customEvent:act'],
                maxResults: 1000,
                dimensionFilter: $filter
            );

            \Cache::set('analytics.song-plays', $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:act'
        );

        $data['table'] = $rows->groupBy('customEvent:act')->map(fn($r) => [
            'slug'  => $r->first()['customEvent:act'],
            'act'   => fractal(Act::whereSlug($r->first()['customEvent:act'])->first(), ActTransformer::class)->toArray(),
            'count' => $r->sum('eventCount'),
        ])->values();

        return response()->json($data);
    }
}
