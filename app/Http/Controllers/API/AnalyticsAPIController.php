<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

/**
 * AnalyticsAPIController
 * A base controller for analytics data endpoints.
 *
 * @package App\Http\Controllers\API
 */
abstract class AnalyticsAPIController extends Controller
{

    const string CACHE_KEY = '';

    abstract protected function analyticsQuery(int $days): Collection;

    abstract protected function analyticsProcessed(?Collection $rows, int $days): array;

    final public function index(): JsonResponse
    {
        $days = request('days', 7);

        $cache_key = 'analytics.' . static::CACHE_KEY . ".$days";
        if (!$rows = Cache::get($cache_key))
        {
            Cache::set($cache_key,
                static::analyticsQuery($days),
                now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = static::analyticsProcessed($rows, $days);

        return response()->json($data);
    }
}
