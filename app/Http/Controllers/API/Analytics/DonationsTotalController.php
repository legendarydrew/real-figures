<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use App\Support\AnalyticsChartFormatter;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * DonationsTotalController
 * This returns analytics data for the cumulative donations total over the specified period.
 * We don't need Google Analytics data for this, as we should have the information required
 * in the database. However, we would want to make sure that the data is a snapshot of the
 * specified period.
 * We would be interested in:
 * - donations per day
 * - Golden Buzzer donations per day
 *
 * @package App\Http\Controllers\API\Analytics
 */
class DonationsTotalController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        // Thanks to https://stackoverflow.com/a/2563940

        $query          = 'SELECT DATE(t.created_at) as date,
                            "%2$s" as type,
                    (SELECT SUM(x.amount) FROM %1$s x WHERE x.id <= t.id) AS amount
                    FROM %1$s t
                    WHERE DATE(t.created_at) > ?
                    GROUP BY date';
        $donations_data = \DB::select(sprintf($query, 'donations', 'd'), [now()->subDays($days)]);
        $buzzer_data    = \DB::select(sprintf($query, 'golden_buzzers', 'b'), [now()->subDays($days)]);
        $rows           = array_map(fn($row) => [
            'date'   => Carbon::parse($row->date),
            'type'   => $row->type,
            'amount' => $row->amount
        ], [...$donations_data, ...$buzzer_data]);

        $data = AnalyticsChartFormatter::stackedByDate($rows, 'type', 'amount');

        // Fill in the gaps, as we're after cumulative totals.
        // For now let's assume that the total will only increase over time.
        $last = [];
        foreach ($data['data'] as &$row) {
            foreach ($data['keys'] as $key) {
                $row[$key] = max($row[$key], $last[$key] ?? 0);
                $last[$key] = $row[$key];
            }
        }
        return response()->json($data);
    }
}
