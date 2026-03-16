<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

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
class DonationsTotalController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        // Thanks to https://stackoverflow.com/a/2563940
        $data = \DB::select('SELECT DATE(t.created_at) as date,
       t.amount,
       (SELECT SUM(x.amount)
        FROM donations x
        WHERE x.id <= t.id) AS total
FROM donations t
WHERE DATE(t.created_at) > ?
GROUP BY date', [now()->subDays($days)]);

        return response()->json(array_values($data));
    }
}
