<?php

use App\Enums\ActRank;

return [
    'anonymous'  => 'Anonymous',
    'stage'      => [
        'status' => [
            'inactive'  => 'Inactive',
            'ready'     => 'Ready',
            'started'   => 'Started',
            'judgement' => 'Judgement',
            'ended'     => 'Ended',
        ],
    ],
    'round'      => [
        'title' => [
            'only_round'  => ':stage_title',
            'many_rounds' => ':stage_title - :round_title',
        ],
    ],
    'song'       => [
        'accolade' => [
            'winner'    => 'Winner of :stage: :round',
            'runner_up' => 'High-scoring runner up in :stage',
        ],
    ],
    'subscriber' => [
        'subject' => 'Real Figures Don\'t F.O.L.D: :title',
    ],
    'act'        => [
        'rank' => [
            ActRank::DOMINANT->value => 'Dominant',
            ActRank::LOVED->value    => 'Loved',
            ActRank::DIVISIVE->value => 'Divisive',
            ActRank::UNDERDOG->value => 'Underdog',
            ActRank::WILDCARD->value => 'Wildcard',
        ]
    ]
];
