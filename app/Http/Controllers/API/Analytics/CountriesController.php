<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\OrderBy;
use Spatie\Analytics\Period;

/**
 * CountriesController
 * This returns analytics data for where the site was accessed over the specified period.
 *
 * @package App\Http\Controllers\API\Analytics
 */
class CountriesController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'countries';

    protected function analyticsQuery(int $days): Collection
    {
        // Same as fetchTopCountries, except we also want the countryId (ISO 3166-1 alpha-2 code).
        // continent is also nice.
        return Analytics::get(
            period: Period::days($days),
            metrics: ['screenPageViews'],
            dimensions: ['country', 'countryId', 'continent'],
            maxResults: 1000,
            orderBy: [
                OrderBy::metric('screenPageViews', true),
            ]);
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $continents = $rows->groupBy('continent')->map(fn($row) => [
            'continent' => $row->first()['continent'],
            'views'     => $row->sum('screenPageViews'),
        ])->sortBy('continent');

        return [
            'continents' => $continents->values(),
            'data'       => $rows->map(fn($row) => [
                'flag'      => $row['countryId'],
                'country'   => $row['country'],
                'continent' => $row['continent'],
                'views'     => $row['screenPageViews']
            ])->toArray()
        ];
    }

}
