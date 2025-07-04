<?php

return [
    'date_format' => 'd/m/Y H:i',
    'donation'    => [
        'default'  => [
            'general'       => 5,
            'golden_buzzer' => 10
        ],
        'minimum' => [
            'general'       => 1,
            'golden_buzzer' => 6
        ],
        'options' => [3, 5, 10, 15, 20, 50, 100],
        'currency' => 'USD'
    ],
    'song'        => [
        'default-title' => 'Real Figures Don\'t F.O.L.D'
    ],
    'points'      => [4, 2, 1],  // points awarded based on rank.
    'rounds'      => [
        'maxDuration' => 31,
        'maxSongs'    => 20,
        'minSongs'    => 3,
    ],
    'judgement'   => [
        'winners'    => 1,
        'runners-up' => 3,
        'allow-ties' => true
    ],
    'ai'          => [
        'model' => 'gpt-4o-mini'
    ]
];
