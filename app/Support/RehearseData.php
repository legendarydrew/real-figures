<?php

namespace App\Support;

class RehearseData
{
    const array STATES = [
        1 => 'Coming soon',
        2 => 'Stage 1: Knockouts - Countdown',
        3 => 'Stage 1: Knockouts - Active',
        4 => 'Stage 1: Knockouts - End',
        5 => 'Stage 2: Finals - Countdown',
        6 => 'Stage 2: Finals - Active',
        7 => 'Stage 2: Finals - End',
        8 => 'Contest over',
    ];

    const array STAGES = [
        [
            'title' => 'Stage 1: Knockouts',
            'description' => 'Eight rounds with four Acts each, to determine which Songs go through to the finals.',
            'golden_buzzer_perks' => 'Acts will be given a profile and a new promotional image.',
        ],
        [
            'title' => 'Stage 2: Finals',
            'description' => 'Qualifying Acts go head-to-head to determine a Grand Winner and three Runners-Up. '.
                'The winning Song becomes the official anthem of the Contest.',
            'golden_buzzer_perks' => 'Acts will be represented as 3D-printed figures in SilentMode\'s style.',
        ],
    ];

    const array ACTS = [
        [
            'name' => 'Airi Kisaragi',
            'subtitle' => null,
            'genres' => ['J-Pop'],
            'traits' => [
                'giggly and coy when giving interviews',
                'youthful',
                'energentic'
            ],
            'languages' => ['ja'],
            'is_fan_favourite' => false,
            'notes' => [
                'Female J-Pop idol in her mid teens.',
                'Known for her colourful hairstyles outfits.',
                'Stylised high-energy metal music.'
            ],
        ],
        [
            'name' => 'Axel King',
            'subtitle' => null,
            'genres' => ['Blues', 'Rock'],
            'traits' => [
                'serious about his craft',
                'passionate about rock and roll',
                'moody when questioned about non-music subjects'
            ],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [
                'A gritty male rock and roller in his late 30s.',
                'Expert guitar player.',
                'Transitioning to blues rock, having discovered the genre.',
                'Performing with his long-time backing band The Roses.'
            ],
        ],
        [
            'name' => 'Bryknii',
            'subtitle' => null,
            'genres' => ['EDM', 'Pop'],
            'traits' => [
                'very much in control of her career',
                'self-assured',
                'wants for nothing',
                'already thinking about future projects'
            ],
            'languages' => ['en'],
            'is_fan_favourite' => true,
            'notes' => [
                'Highly popular female pop icon in her early 30s.',
                'Relies heavily on autotuning and other modern production values.',
                'Has a bitter rivalry with Saima Gaines.'
            ],
        ],
        [
            'name' => 'Bryknii',
            'subtitle' => 'ft. Kat Soo',
            'genres' => ['EDM', 'K-Pop', 'Pop'],
            'traits' => [
                'Kat Soo: sassy, unashamedly female, highly suggestive',
                'Bryknii: admires Kat Soo but primarily sees the pairing as another chance to win'
            ],
            'languages' => ['en', 'ko'],
            'is_fan_favourite' => false,
            'notes' => [
                'Bryknii is CATAWOL Records\' biggest and most popular Act, female in her early 30s.',
                'Kat Soo is an up and coming K-Pop singer/rapper in her late teens.'
            ],
        ],
        ['name' => 'Buck & Jeb',
            'subtitle' => null,
            'genres' => ['Country & Western'],
            'traits' => [
                'generally easygoing',
                'do not take themselves too seriously',
                'derives satisfaction from entertaining the crowd',
                'rely on and compliment each other as a duo',
            ],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [
                'Male country and western duo in their 40s.',
                'Competent but known for their comedic take on the genre.'
            ],
        ],
        ['name' => 'BZpeople',
            'subtitle' => null,
            'genres' => ['EDM'],
            'traits' => [
                'down to earth',
                'results oriented',
                'celebrity is a means to an end'
            ],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [
                'A solo black male house producer in his late 30s.',
                'Usually works entirely on his own.',
                'Emphasise messages with catchy hooks in his songs.',
            ],
        ],
        ['name' => 'Chelsea Chapel',
            'subtitle' => null,
            'genres' => ['Classical'],
            'traits' => [
                'quiet confidence',
                'humble but aware of her abilities',
                'noble and refined'
            ],
            'languages' => ['en', 'ja'],
            'is_fan_favourite' => false,
            'notes' => [
                'A mixed-race female classical singer in her early 20s.',
                'Most remembered for her plus-sized figure.',
                'Does not often do interviews.'
            ],
        ],
        [
            'name' => 'Cielo Groove',
            'subtitle' => 'ft. Saima Gaines',
            'genres' => ['Latin', 'R&B'],
            'traits' => [
                'Cielo Groove: laid-back, enthusiastic about the pairing and Latin music',
                'Saima Gaines: somewhat sassy, resentful of Bryknii'
            ],
            'languages' => ['en', 'es'],
            'is_fan_favourite' => false,
            'notes' => [
                'Cielo Groove is an all-male Latin group in their 30s.',
                'Saima Gaines is an established R&B singer in her early 30s.',
                'The pairing was formed through a mutual respect for music styles.',
                'Cielo Groove is known for their catchy, self-styled "Latin persuasion" music.',
                'Saima was previously known for being autotuned, now looking to establish herself as a bona fide singer.'
            ],
        ],
        [
            'name' => 'Clémence Duval',
            'subtitle' => null,
            'genres' => [],
            'traits' => [],
            'languages' => ['en', 'fr'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Coastliners, the',
            'subtitle' => null,
            'genres' => ['Barbershop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Elora James',
            'subtitle' => null,
            'genres' => ['Soul'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Emma Finch',
            'subtitle' => null,
            'genres' => ['Americana', 'Folk'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Forty Twos, the',
            'subtitle' => null,
            'genres' => ['Barbershop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'GRMLN',
            'subtitle' => null,
            'genres' => ['EDM'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'High School Dropout',
            'subtitle' => null,
            'genres' => ['Rock'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => true,
            'notes' => ['Has the closest relationship to the MODE Family of all the Acts.'],
        ],
        [
            'name' => 'Kendra Blaze',
            'subtitle' => null,
            'genres' => ['Pop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Loop Theory',
            'subtitle' => null,
            'genres' => ['Hip Hop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Lorien',
            'subtitle' => null,
            'genres' => ['Downtempo', 'EDM'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Magenta Men',
            'subtitle' => null,
            'genres' => ['Funk', 'Soul'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Marissa Wild',
            'subtitle' => null,
            'genres' => ['Pop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => ['Came out of retirement to enter the Contest.'],
        ],
        [
            'name' => 'Max Bellamy',
            'subtitle' => 'and The Moonlight Swingers',
            'genres' => ['Big Band', 'Swing'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Miles Everly',
            'subtitle' => null,
            'genres' => ['Soft Rock'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Raya Vibes',
            'subtitle' => null,
            'genres' => ['Reggae', 'R&B'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'RJ "Hound" Mercer',
            'subtitle' => null,
            'genres' => ['Blues'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Soline Bellefort',
            'subtitle' => null,
            'genres' => [],
            'traits' => [],
            'languages' => ['fr'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Sonder Drift',
            'subtitle' => null,
            'genres' => ['Downtempo', 'EDM'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => ['Only recently signed to CATAWOL Records after success as an independent Act.'],
        ],
        [
            'name' => 'SoraNami',
            'subtitle' => null,
            'genres' => ['J-Pop'],
            'traits' => [],
            'languages' => ['en', 'ja'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Synth & Son',
            'subtitle' => null,
            'genres' => ['EDM', 'Pop'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Vexon',
            'subtitle' => null,
            'genres' => ['EDM'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Violet Riot',
            'subtitle' => null,
            'genres' => ['Punk', 'Rock'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Violeta Montenegro',
            'subtitle' => null,
            'genres' => ['Latin'],
            'traits' => [],
            'languages' => ['en', 'es'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
        [
            'name' => 'Westbound',
            'subtitle' => null,
            'genres' => ['Pop', 'Rock'],
            'traits' => [],
            'languages' => ['en'],
            'is_fan_favourite' => false,
            'notes' => [],
        ],
    ];
}
