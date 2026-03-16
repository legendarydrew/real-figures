<?php

return [
    'format'    => [
        'date'      => 'd/m/Y',
        'full-date' => 'd/m/Y H:i',
    ],
    'donation'  => [
        'default'       => [
            'general'       => 5,
            'golden_buzzer' => 10
        ],
        'minimum'       => [
            'general'       => 1,
            'golden_buzzer' => 6
        ],
        'options'       => [3, 5, 10, 15, 20, 50, 100],
        'currency'      => 'USD',
        'target_amount' => env('CONTEST_DONATION_TARGET', 0)  // [optional] target amount to raise.
    ],
    'song'      => [
        'default-title' => 'Real Figures Don\'t F.O.L.D'
    ],
    'points'    => [4, 2, 1],  // points awarded based on rank.
    'rounds'    => [
        'maxDuration' => 31,
        'maxSongs'    => 20,
        'minSongs'    => 3,
    ],
    'judgement' => [
        'winners'       => 1,
        'runners-up'    => 3,
        'allow-ties'    => env('CONTEST_ALLOW_TIES', true),
        'panel-count' => env('CONTEST_PANEL_COUNT', 0),  // number of additional "panel" members for manual voting.
        'panel-bias'    => env('CONTEST_PANEL_BIAS', 50),  // 0-100 for how biased the "panel" will be toward the manual vote.
    ],
    'ai'        => [
        'model' => 'gpt-4o-mini'
    ],
    'images'    => [
        'subfolder' => 'act',
        'resize'    => [1000, 1000]
    ],
    'analytics' => [
        'cache' => 20 // minutes
    ]
];
