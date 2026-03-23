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
        if (empty(static::CACHE_KEY)) {
            abort(400, 'Undefined CACHE_KEY.');
        }

        $days = request('days', 7);

        $cache_key = 'analytics.' . static::CACHE_KEY . ".$days";
        if (!$rows = Cache::get($cache_key))
        {
            $rows = $this->analyticsQuery($days);
            Cache::set($cache_key,
                $rows,
                now()->plus(minutes: config('contest.analytics.cache', 60)));
        }

        $data = static::analyticsProcessed($rows, $days);

        return response()->json($data);
    }

    /**
     * Fill in any gaps for stacked chart data.
     * @param array $stacked_data
     * @param int   $days
     * @return void
     */
    protected function fillDateGaps(array &$stacked_data, int $days): void
    {
        $data   = [];
        $end    = now();
        $cursor = $end->copy()->subDays($days);
        $empty_row = array_fill_keys($stacked_data['keys'], 0); // nice!
        while ($cursor->lte($end))
        {
            $current_date        = $cursor->format('Y-m-d');
            $matching_row        = array_find($stacked_data['data'], fn($row) => $row['date'] === $current_date);
            $data[$current_date] = ['date' => $cursor->toISOString(), ...($matching_row ?? $empty_row)];
            $cursor->addDay();
        }
        ksort($data); // sort by ascending date.
        $stacked_data['data'] = array_values($data);
    }
}
