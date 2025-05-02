<?php

return [
    'date_format' => 'd/m/Y H:i',
    'contact'  => [
        'captcha_threshold' => 0.5, // anything lower is considered spam.
    ],
    'donation' => [
        'default'  => [
            'general'       => 5,
            'golden_buzzer' => 10
        ],
        'options'  => [2, 5, 10, 15, 20],
        'currency' => 'USD'
    ],
    'song'     => [
        'default-title' => 'Real Figures Don\'t F.O.L.D'
    ],
    'points'   => [4, 2, 1],  // points awarded based on rank.
    'rounds'   => [
        'maxDuration' => 31,
        'maxSongs'    => 20,
        'minSongs' => 3,
    ]
];
