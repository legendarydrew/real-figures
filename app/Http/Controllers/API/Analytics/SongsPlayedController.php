<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\API\AnalyticsAPIController;
use App\Models\Act;
use App\Support\AnalyticsChartFormatter;
use App\Transformers\ActTransformer;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Support\Collection;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

/**
 * SongsPlayedController
 * This returns analytics data for which Songs were played (through this site) over the specified period.
 * We would be interested in:
 * - Songs played per day
 * - which Songs were played, and how many times.
 */
class SongsPlayedController extends AnalyticsAPIController
{
    const string CACHE_KEY = 'song-plays';

    protected function analyticsQuery(int $days): Collection
    {
        $filter = new FilterExpression([
            'filter' => new Filter([
                'field_name' => 'eventName',
                'string_filter' => new Filter\StringFilter([
                    'match_type' => Filter\StringFilter\MatchType::EXACT,
                    'value' => 'song_play',
                ]),
            ]),
        ]);

        return Analytics::get(
            period: Period::days($days),
            metrics: ['eventCount'],
            dimensions: ['date', 'customEvent:act'],
            maxResults: 1000,
            dimensionFilter: $filter
        );
    }

    protected function analyticsProcessed(?Collection $rows, int $days): array
    {
        $stacked_data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:act'
        );

        // Fill in the gaps (dates).
        $this->fillDateGaps($stacked_data, $days);

        $stacked_data['table'] = $rows->groupBy('customEvent:act')->map(fn ($r) => [
            'slug' => $r->first()['customEvent:act'],
            'act' => fractal(Act::whereSlug($r->first()['customEvent:act'])->first(), ActTransformer::class)->toArray(),
            'count' => $r->sum('eventCount'),
        ])->values();

        return $stacked_data;
    }
}
