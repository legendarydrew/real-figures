<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Collection;


/**
 * AnalyticsChartFormatter
 *
 * created by ChatGPT, modified by me.
 *
 * @package App\Support
 */
class AnalyticsChartFormatter
{

    public static function byHour(array|Collection $rows): array
    {
        $data = collect($rows)
            ->map(fn($row) => [
                'time'  => Carbon::createFromFormat('YmdH', $row['dateHour'])->format('Y-m-d H:00'),
                'count' => (int)$row['eventCount']
            ])
            ->sortBy('time')
            ->values();

        // Determine date range
        $start = now()->subDays(7);
        $end   = now();

        $dates = $data->pluck('time');

        $cursor  = $start->copy();
        while ($cursor->lte($end))
        {
            $date = $cursor->format('Y-m-d H:00');
            if (!$dates->contains($date)) {
                $data->push(['time' => $date, 'count' => 0]);
            }
            $cursor->addHour();
        }

        return $data->sortBy('time')->values()->toArray();
    }

    public static function byDate(array|Collection $rows): array
    {
        $data = collect($rows)
            ->map(fn($row) => [
                'time'  => $row['date'],
                'count' => (int)$row['eventCount']
            ])
            ->sortBy('time')
            ->values();

        // Determine date range
        $start = now()->subDays(7);
        $end   = now();

        $dates = $data->pluck('time');

        $cursor  = $start->copy();
        while ($cursor->lte($end))
        {
            $date = $cursor->format('Y-m-d H:00');
            if (!$dates->contains($date)) {
                $data->push(['time' => $date, 'count' => 0]);
            }
            $cursor->addHour();
        }

        return $data->sortBy('time')->values()->toArray();
    }

    public static function stackedByDate(
        array|Collection $rows,
        string           $dimension,
        string           $metric = 'eventCount',
        string           $interval = 'day',
        ?int             $top = null
    ): array
    {
        // Organise the data into combinations of date and dimension values.
        $rows = collect($rows)->map(function ($row) use ($dimension, $metric, $interval)
        {
            $date = $row['date'];
            if ($interval === 'week')
            {
                $dateKey = $date->copy()->startOfWeek()->toDateString();
            }
            elseif ($interval === 'month')
            {
                $dateKey = $date->copy()->startOfMonth()->toDateString();
            }
            else
            {
                $dateKey = $date->toDateString();
            }

            return [
                'date'      => $dateKey,
                'dimension' => $row[$dimension] ?? 'unknown',
                'value'     => (int)$row[$metric],
            ];
        });

        if ($rows->isEmpty())
        {
            return ['data' => [], 'keys' => []];
        }

        // Determine top dimensions if requested
        if (!is_null($top))
        {
            $topKeys = $rows
                ->groupBy('dimension')
                ->map(fn($g) => $g->sum('value'))
                ->sortDesc()
                ->take($top)
                ->keys();

            $rows = $rows->map(function ($row) use ($topKeys)
            {
                if (!$topKeys->contains($row['dimension']))
                {
                    $row['dimension'] = 'Other';
                }
                return $row;
            });
        }

        $keys = $rows->pluck('dimension')->unique()->values();

        // Sum values per date + dimension
        // ChatGPT made an error here: grouping by date and dimension produces
        // a collection of collections, so we have to map at both levels.
        $grouped = $rows->groupBy(['date', 'dimension'])
                        ->map(fn($d) => $d->map(fn($g) => $g->sum('value')));

        // Determine date range
        $start = Carbon::parse($rows->min('date'));
        $end   = Carbon::parse($rows->max('date'));

        $periods = collect();
        $cursor  = $start->copy();

        while ($cursor->lte($end))
        {
            $periods->push($cursor->toDateString());

            match ($interval)
            {
                'week' => $cursor->addWeek(),
                'month' => $cursor->addMonth(),
                default => $cursor->addDay()
            };
        }

        // Build chart rows
        $chartData = $periods->map(function ($date) use ($grouped, $keys)
        {
            $row = ['date' => $date];

            foreach ($keys as $key)
            {
                $row[$key] = $grouped[$date][$key] ?? 0;
            }

            return $row;
        });

        return [
            'keys' => $keys->values()->toArray(),
            'data' => $chartData->values()->toArray()
        ];
    }
}
