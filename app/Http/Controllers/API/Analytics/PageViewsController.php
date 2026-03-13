<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * PageViewsController
 * This returns analytics data for page views over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class PageViewsController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.page-views.' . $days))
        {
            $rows = Analytics::fetchTotalVisitorsAndPageViews(
                period: Period::days($days),
                maxResults: 1000
            );

            \Cache::set('analytics.page-views.' . $days, $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data          = $rows->map(fn($row) => [
            'date'     => $row['date']->format('Y-m-d'),
            'views'    => $row['screenPageViews'],
            'visitors' => $row['activeUsers'],
        ])->reverse();


        return response()->json($data->values());
    }
}
