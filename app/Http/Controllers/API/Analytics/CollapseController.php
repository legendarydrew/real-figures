<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use App\Support\AnalyticsChartFormatter;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Illuminate\Http\JsonResponse;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class CollapseController extends Controller
{

    public function index(): JsonResponse
    {
        $days = request('days', 7);

        // Building on ChatGPT's suggestion.

        // Filter results by the event name (in this case, we defined 'collapse_open').
        // Custom events have to be set up in Google Analytics:
        //    Admin → Custom definitions → Create custom dimension
        // e.g.
        //    Dimension name: Section ID
        //    Event parameter: section_id
        //    Scope: Event

        if (!$rows = \Cache::get('analytics.collapse'))
        {
            $filter = new FilterExpression([
                'filter' => new Filter([
                    'field_name'    => 'eventName',
                    'string_filter' => new Filter\StringFilter([
                        'match_type' => Filter\StringFilter\MatchType::EXACT,
                        'value'      => 'collapse_open',
                    ]),
                ]),
            ]);

            $rows = Analytics::get(
                period: Period::days($days),
                metrics: ['eventCount'],
                dimensions: ['date', 'pageTitle', 'customEvent:section_id'],
                maxResults: 1000,
                dimensionFilter: $filter
            );

            \Cache::set('analytics.collapse', $rows, 600);
        }

        $data = AnalyticsChartFormatter::stackedByDate(
            $rows,
            'customEvent:section_id'
        );

        return response()->json($data);
    }
}
