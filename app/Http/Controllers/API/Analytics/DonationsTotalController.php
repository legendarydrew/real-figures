<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
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
