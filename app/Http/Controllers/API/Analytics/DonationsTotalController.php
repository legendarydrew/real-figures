<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use App\Support\AnalyticsChartFormatter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * DonationsTotalController
 * This returns analytics data for the cumulative donations total over the specified period.
 * We don't need Google Analytics data for this, as we should have the information required
 * in the database. However, we would want to make sure that the data is a snapshot of the
 * specified period.
 * We would be interested in:
 * - donations per day
 * - Golden Buzzer donations per day
 */
class DonationsTotalController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'donations_total';

    protected function analyticsQuery(int $days): Collection
    {
        // Thanks to https://stackoverflow.com/a/2563940
        $query = 'SELECT DATE(t.created_at) as date,
                            "%2$s" as type,
                    (SELECT SUM(x.amount) FROM %1$s x WHERE x.id <= t.id) AS amount
                    FROM %1$s t
                    WHERE DATE(t.created_at) > ?
                    GROUP BY date';
        $donations_data = DB::select(sprintf($query, 'donations', 'd'), [now()->subDays($days)]);
        $buzzer_data = DB::select(sprintf($query, 'golden_buzzers', 'b'), [now()->subDays($days)]);

        return collect(['donations' => $donations_data, 'buzzers' => $buzzer_data]);
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $data_rows = array_map(fn ($row) => [
            'date' => Carbon::parse($row->date),
            'type' => $row->type,
            'amount' => $row->amount,
        ], [...$rows['donations'], ...$rows['buzzers']]);

        $stacked_data = AnalyticsChartFormatter::stackedByDate($data_rows, 'type', 'amount');

        // We're after cumulative totals for these analytics, so we want to fill in the gaps between dates
        // and include missing dates.
        // For now, let's assume that the total donation amount will only increase over time.

        $data = [];
        $end = now()->startOfDay();
        $cursor = $end->copy()->subDays($days);
        while ($cursor->lte($end)) {
            $current_date = $cursor->toISOString();
            $matching_row = array_find($stacked_data['data'], fn ($row) => $row['date'] === $current_date);
            $data[$current_date] = ['date' => $current_date, 'd' => $matching_row['d'] ?? 0, 'b' => $matching_row['b'] ?? 0];
            $cursor->addDay();
        }
        ksort($data); // sort by ascending date.

        $last = [];
        foreach ($data as &$row) {
            foreach ($stacked_data['keys'] as $key) {
                $row[$key] = max($row[$key], $last[$key] ?? 0);
                $last[$key] = $row[$key];
            }
        }
        $stacked_data['data'] = array_values($data);

        return $stacked_data;
    }
}
