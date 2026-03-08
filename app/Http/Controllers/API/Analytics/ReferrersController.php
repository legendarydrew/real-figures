<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * ReferrersController
 * This returns analytics data about where Visitors came from, in terms of site links.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class ReferrersController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        if (!$rows = \Cache::get('analytics.referrers'))
        {
            $rows = Analytics::fetchTopReferrers(
                period: Period::days($days),
                maxResults: 1000
            );

            \Cache::set('analytics.referrers', $rows, now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data['table'] = $rows->map(fn($r) => [
            'referrer' => $r['pageReferrer'],
            'count' => $r['screenPageViews'],
        ])->values();

        return response()->json($data);
    }
}
