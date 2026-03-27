<?php

namespace App\Http\Controllers\API\Analytics;

/**
 * GoldenBuzzersController
 * This returns analytics data for Golden Buzzers started and completed over the specified period.
 * The hard work was done with a similar report for donations.
 * We would be interested in:
 * - Golden Buzzers started per day
 * - Golden Buzzers completed per day
 */
class GoldenBuzzersMadeController extends DonationsMadeController
{
    const string CACHE_KEY = 'golden-buzzers-made';

    const string DIALOG_ID = 'golden_buzzer';

    const string EVENT_NAME = 'golden_buzzer';
}
