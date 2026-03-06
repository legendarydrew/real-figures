<?php

namespace App\Http\Controllers\API\Analytics;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
            dimensions: ['date', 'customEvent:section_id'],
            maxResults: 1000,
            dimensionFilter: $filter
        );

        $data = collect($rows)
            ->groupBy(['customEvent:section_id'])
            ->map(function ($events)
            {
                return $events->map(fn($row) => [
                    'date'  => Carbon::createFromFormat('Ymd', $row['date'])->toDateString(),
                    'count' => (int)$row['eventCount']
                ]);
            });

        return response()->json($data->values());
    }
}
