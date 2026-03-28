<?php

return [
    'format'    => [
        'date'      => 'd/m/Y',
        'full-date' => 'd/m/Y H:i',
    ],
    'donation'  => [
        'default'       => [
            'general'       => 5,
            'golden_buzzer' => 10,
        ],
        'minimum'       => [
            'general'       => 1,
            'golden_buzzer' => 6,
        ],
        'options'       => [3, 5, 10, 15, 20, 50, 100],
        'currency'      => 'USD',
        'target_amount' => env('CONTEST_DONATION_TARGET', 0),  // [optional] target amount to raise.
    ],
    'song'      => [
        'default-title' => 'Real Figures Don\'t F.O.L.D',
    ],
    'points'    => [4, 2, 1],  // points awarded based on rank.
    'rounds'    => [
        'maxDuration' => 31,
        'maxSongs'    => 20,
        'minSongs'    => 3,
    ],
    'judgement' => [
        'winners'     => 1,
        'runners-up'  => 3,
        'allow-ties'  => env('CONTEST_ALLOW_TIES', true),
        'panel-count' => env('CONTEST_PANEL_COUNT', 0),
        // number of "panel" members for manual voting, in addition to the user.
        'panel-bias'  => env('CONTEST_PANEL_BIAS', 50),
        // 0-100 for how biased the "panel" will be toward the manual vote.
    ],
    'ai'        => [
        'model'       => 'gpt-4o-mini',
        'temperature' => [
            // controls the level of "creativity"/"weirdness" in responses (0 = restrained, 1 = risky).
            \App\Enums\NewsPostType::GENERAL->value => 0.6,
            \App\Enums\NewsPostType::CONTEST->value => 0.5,
            \App\Enums\NewsPostType::ACT->value     => 0.75,
            \App\Enums\NewsPostType::RESULTS->value => 0.5,
            \App\Enums\NewsPostType::STAGE->value   => 0.4,
            \App\Enums\NewsPostType::ROUND->value   => 0.6,
        ],
        'retry' => [
            'attempts' => 3,
            'backoff_ms' => 150, // small delay between retries
        ],
    ],
    'images'    => [
        'subfolder' => 'act',
        'resize'    => [1000, 1000],
    ],
    'analytics' => [
        'cache' => 10, // minutes
    ],
    'feed'      => [
        'title'       => 'CATAWOL Records presents Real Figures Don\'t F.O.L.D',
        'description' => 'The latest news from the CATAWOL Records Song Contest — announcements, round results, artist highlights, and behind-the-scenes stories.',
        'author'      => 'CATAWOL Records',
        'email'       => 'holla@silentmode.tv',
    ],
];
