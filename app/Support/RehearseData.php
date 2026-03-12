<?php
namespace App\Support;

class RehearseData
{
    const STATES = [
        1 => 'Coming soon',
        2 => 'Stage 1: Knockouts - Countdown',
        3 => 'Stage 1: Knockouts - Active',
        4 => 'Stage 1: Knockouts - End',
        5 => 'Stage 2: Finals - Countdown',
        6 => 'Stage 2: Finals - Active',
        7 => 'Stage 2: Finals - End',
        8 => 'Contest over'
    ];

    const STAGES = [
        [
            'title'               => 'Stage 1: Knockouts',
            'description'         => 'Eight rounds with four Acts each, to determine which Songs go through to the finals.',
            'golden_buzzer_perks' => 'Acts will be given a profile and a new promotional image.'
        ],
        [
            'title'               => 'Stage 2: Finals',
            'description'         => 'Qualifying Acts go head-to-head to determine a Grand Winner and three Runners-Up. ' .
                'The winning Song becomes the official anthem of the Contest.',
            'golden_buzzer_perks' => 'Acts will be represented as 3D-printed figures in SilentMode\'s style.'
        ]
    ];

    const ACTS = [
        [
            'name'             => 'Airi Kisaragi',
            'subtitle'         => null,
            'genres'           => ['J-Pop'],
            'traits'           => [],
            'languages'        => ['jp'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Axel King',
            'subtitle'         => null,
            'genres'           => ['Blues', 'Rock'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Bryknii',
            'subtitle'         => null,
            'genres'           => ['EDM', 'Pop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => true,
            'notes'            => ['Has a bitter rivalry with Saima Gaines.']
        ],
        [
            'name'             => 'Bryknii',
            'subtitle'         => 'ft. Kat Soo',
            'genres'           => ['EDM', 'K-Pop', 'Pop'],
            'traits'           => [],
            'languages'        => ['en', 'ko'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        ['name'             => 'Buck & Jeb',
         'subtitle'         => null,
         'genres'           => ['Country & Western'],
         'traits'           => [],
         'languages'        => ['en'],
         'is_fan_favourite' => false,
         'notes'            => []
        ],
        ['name'             => 'BZpeople',
         'subtitle'         => null,
         'genres'           => ['EDM'],
         'traits'           => [],
         'languages'        => ['en'],
         'is_fan_favourite' => false,
         'notes'            => []
        ],
        ['name'             => 'Chelsea Chapel',
         'subtitle'         => null,
         'genres'           => ['Classical'],
         'traits'           => [],
         'languages'        => ['en', 'jp'],
         'is_fan_favourite' => false,
         'notes'            => []
        ],
        [
            'name'             => 'Cielo Groove',
            'subtitle'         => 'ft. Saima Gaines',
            'genres'           => ['Latin', 'R&B'],
            'traits'           => [],
            'languages'        => ['en', 'es'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Clémence Duval',
            'subtitle'         => null,
            'genres'           => [],
            'traits'           => [],
            'languages'        => ['en', 'fr'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Coastliners, the',
            'subtitle'         => null,
            'genres'           => ['Barbershop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Elora James',
            'subtitle'         => null,
            'genres'           => ['Soul'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Emma Finch',
            'subtitle'         => null,
            'genres'           => ['Americana', 'Folk'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Forty Twos, the',
            'subtitle'         => null,
            'genres'           => ['Barbershop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'GRMLN',
            'subtitle'         => null,
            'genres'           => ['EDM'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'High School Dropout',
            'subtitle'         => null,
            'genres'           => ['Rock'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => true,
            'notes'            => ['Has the closest relationship to the MODE Family of all the Acts.']
        ],
        [
            'name'             => 'Kendra Blaze',
            'subtitle'         => null,
            'genres'           => ['Pop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Loop Theory',
            'subtitle'         => null,
            'genres'           => ['Hip Hop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Lorien',
            'subtitle'         => null,
            'genres'           => ['Downtempo', 'EDM'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Magenta Men',
            'subtitle'         => null,
            'genres'           => ['Funk', 'Soul'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Marissa Wild',
            'subtitle'         => null,
            'genres'           => ['Pop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => ['Came out of retirement to enter the Contest.']
        ],
        [
            'name'             => 'Max Bellamy',
            'subtitle'         => 'and The Moonlight Swingers',
            'genres'           => ['Big Band', 'Swing'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Miles Everly',
            'subtitle'         => null,
            'genres'           => ['Soft Rock'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Raya Vibes',
            'subtitle'         => null,
            'genres'           => ['Reggae', 'R&B'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'RJ "Hound" Mercer',
            'subtitle'         => null,
            'genres'           => ['Blues'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Soline Bellefort',
            'subtitle'         => null,
            'genres'           => [],
            'traits'           => [],
            'languages'        => ['fr'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Sonder Drift',
            'subtitle'         => null,
            'genres'           => ['Downtempo', 'EDM'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => ['Only recently signed to CATAWOL Records after success as an independent Act.']
        ],
        [
            'name'             => 'SoraNami',
            'subtitle'         => null,
            'genres'           => ['J-Pop'],
            'traits'           => [],
            'languages'        => ['en', 'jp'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Synth & Son',
            'subtitle'         => null,
            'genres'           => ['EDM', 'Pop'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Vexon',
            'subtitle'         => null,
            'genres'           => ['EDM'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Violet Riot',
            'subtitle'         => null,
            'genres'           => ['Punk', 'Rock'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Violeta Montenegro',
            'subtitle'         => null,
            'genres'           => ['Latin'],
            'traits'           => [],
            'languages'        => ['en', 'es'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
        [
            'name'             => 'Westbound',
            'subtitle'         => null,
            'genres'           => ['Pop', 'Rock'],
            'traits'           => [],
            'languages'        => ['en'],
            'is_fan_favourite' => false,
            'notes'            => []
        ],
    ];
}
