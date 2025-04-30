<?php

return [
    'stage' => [
        'status' => [
            'inactive'  => 'Inactive',
            'ready'     => 'Ready',
            'started'   => 'Started',
            'judgement' => 'Judgement',
            'ended'     => 'Ended',
        ]
    ],
    'round' => [
        'title' => [
            'only_round'  => ':stage_title',
            'many_rounds' => ':stage_title: :round_title',
        ]
    ],
    'song'  => [
        'accolade' => [
            'winner'    => 'Winner of :stage: :round',
            'runner_up' => 'High-scoring runner up in :stage',
        ]
    ]
];
