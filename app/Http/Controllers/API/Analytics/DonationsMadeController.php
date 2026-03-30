<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * DonationsController
 * This returns analytics data for donations started and completed over the specified period.
 * This one is trickier, because we would have to combine two reports.
 * We would be interested in:
 * - donations started per day
 * - donations completed per day
 */
class DonationsMadeController extends AnalyticsAPIController
{
    public const string CACHE_KEY = 'donations-made';

    const string        DIALOG_ID = 'donate';

    const string        EVENT_NAME = 'donation';

    protected function analyticsQuery(int $days): Collection
    {
        // For these reports, we essentially want to fetch two sets of results.
        // We can do this with two separate Analytics calls, but we ran into problems with testing:
        // it's not possible to fake multiple calls.
        // Instead, we will have to do some extra processing from consolidated results.
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'eventName',
                'in_list_filter' => new Filter\InListFilter([
                    'values' => [
                        'dialog_open',      // looking for opened dialog events.
                        static::EVENT_NAME, // looking for donation events.
                    ],
                ]),
            ]),
        ]);

        $rows = Analytics::get(
            Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'eventName', 'customEvent:type'],
            maxResults: 1000,
            dimensionFilter: $filter,
            keepEmptyRows: true
        );

        return collect($rows);
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        // rows should contain:
        // - date
        // - eventName
        // - customEvent:type
        // - eventCount
        $rows = $rows->filter(fn ($row) => $row['eventName'] === static::EVENT_NAME || $row['customEvent:type'] === static::DIALOG_ID);

        // Build a list of dates and corresponding values.
        $data = [];
        $end = now()->startOfDay();
        $cursor = $end->copy()->subDays($days);
        while ($cursor->lte($end)) {
            $date = $cursor->toISOString();
            $data[$date] = ['date' => $date, 'started' => 0, 'completed' => 0];
            $cursor->addDay();
        }

        // Add the respective values for each date.
        $rows->each(function ($row) use (&$data) {
            $date = $row['date']->startOfDay()->toISOString();
            $key = $row['eventName'] === static::EVENT_NAME ? 'completed' : 'started';
            $data[$date][$key] = $row['eventCount'];
        });

        return array_values($data);
    }
}
