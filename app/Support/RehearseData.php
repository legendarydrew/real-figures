<?php

namespace App\Support;

use App\Enums\ActRank;

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
            'title'               => 'Stage 1: Knockouts',
            'description'         => 'Eight Rounds featuring four Acts each, competing to determine which Songs go through to the Finals.',
            'golden_buzzer_perks' => 'Acts will be given a profile and a new promotional image.',
        ],
        [
            'title'               => 'Stage 2: Finals',
            'description'         => 'Qualifying Acts going head-to-head to determine a Grand Winner and three Runners-Up. ' .
                'The winning Song becomes the official anthem of the Contest.',
            'golden_buzzer_perks' => 'Acts will be represented as 3D-printed figures in SilentMode\'s style.',
        ],
    ];

    const array ACTS = [
        [
            'name'             => 'Airi Kisaragi',
            'subtitle'         => null,
            'genres'           => ['J-Pop'],
            'song'             => [
                'title'    => 'Jinbutsu Wa Kusshinai',
                'language' => 'ja',
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/dQriLqZRDks',
                ]
            ],
            'traits'           => [
                'giggly and coy when giving interviews',
                'youthful',
                'energetic',
                'admires SoraNami\'s independence'
            ],
            'languages'        => ['ja'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female J-Pop idol in her mid teens.',
                'Known for her colourful hairstyles and outfits.',
                'Stylised high-energy metal music.',
                ''
            ],
        ],
        [
            'name'             => 'Axel King',
            'subtitle'         => null,
            'genres'           => ['Blues', 'Rock'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/cvLOkscTVLU',
                    'live' => 'https://youtu.be/qANo6DHk9uM',
                ]
            ],
            'traits'           => [
                'serious about his craft',
                'passionate about rock and roll',
                'moody when questioned about non-music subjects'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'A gritty male rock and roller in his late 30s.',
                'Expert guitar player.',
                'Transitioning to blues rock, having discovered the genre.',
                'Performing with his long-time backing band The Roses.'
            ],
        ],
        [
            'name'             => 'Bryknii',
            'subtitle'         => null,
            'genres'           => ['EDM', 'Pop'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/PPrxN0D20Aw',
                ]
            ],
            'traits'           => [
                'very much in control of her career',
                'self-assured',
                'wants for nothing',
                'already thinking about future projects',
                // Suggestions from ChatGPT.
                'treats success as expected rather than earned',
                'rarely acknowledges competitors unless necessary'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DOMINANT,
            'is_fan_favourite' => true,
            'notes'            => [
                'Highly popular female pop icon in her early 30s.',
                'Relies heavily on autotuning and other modern production values.',
                'Has a bitter rivalry with Saima Gaines.'
            ],
        ],
        [
            'name'             => 'Bryknii',
            'subtitle'         => 'ft. Kat Soo',
            'genres'           => ['EDM', 'K-Pop', 'Pop'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/ZlpnKdnDl6I',
                    'live' => 'https://youtu.be/jDzKh8OL2Hs',
                ]
            ],
            'traits'           => [
                'Kat Soo: sassy, unashamedly female, highly suggestive',
                'Bryknii: admires Kat Soo but primarily sees the pairing as another chance to win'
            ],
            'languages'        => ['en', 'ko'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Bryknii is CATAWOL Records\' biggest and most popular Act, female in her early 30s.',
                'Kat Soo is an up and coming K-Pop singer/rapper in her late teens.'
            ],
        ],
        [
            'name'             => 'Buck & Jeb',
            'subtitle'         => null,
            'genres'           => ['Country & Western'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/XaRc36LRhio',
                    'live' => 'https://youtu.be/DFYvL_Dji5s',
                ]
            ],
            'traits'           => [
                'generally easygoing',
                'do not take themselves too seriously',
                'derives satisfaction from entertaining the crowd',
                'rely on and compliment each other as a duo',
                'appreciation of Synth & Son for working as a pair'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male country and western duo in their 40s.',
                'Competent but known for their comedic take on the genre.'
            ],
        ],
        [
            'name'             => 'BZpeople',
            'subtitle'         => null,
            'genres'           => ['EDM'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/_OghkKOfT88',
                    'live' => 'https://youtu.be/6hDGsauucow',
                ]
            ],
            'traits'           => [
                'down to earth',
                'results oriented',
                'celebrity is a means to an end',
                'aware he might be an underdog',
                // Suggestions from ChatGPT.
                'quietly confident in his process',
                'measures success differently from mainstream Acts',
                'sees GRMLN as “one of the few doing it right”'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'A solo black male house producer in his late 30s.',
                'Usually works entirely on his own.',
                'Emphasise messages with catchy hooks in his songs.',
            ],
        ],
        [
            'name'             => 'Chelsea Chapel',
            'subtitle'         => null,
            'genres'           => ['Classical'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/a2-xiGQdsRU',
                ]
            ],
            'traits'           => [
                'quiet confidence',
                'humble but aware of her abilities',
                'noble, complimentary of other Acts',
                'appreciates Clémence\'s composure'
            ],
            'languages'        => ['en', 'ja'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'An English/Japanese female contemporary classical singer in her mid 20s.',
                'Most remembered for her plus-sized figure.',
                'Does not often do interviews.'
            ],
        ],
        [
            'name'             => 'Cielo Groove',
            'subtitle'         => 'ft. Saima Gaines',
            'genres'           => ['Latin', 'R&B'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/AfamktDyxzE',
                ]
            ],
            'traits'           => [
                'Cielo Groove: laid-back, enthusiastic about the pairing and Latin music',
                'Cielo Groove: respects Violeta\'s legacy',
                'Saima Gaines: somewhat sassy, resentful of Bryknii and her assured fame',
                // Suggestions from ChatGPT.
                'Saima Gaines: determined to prove she does not rely on production tricks',
                'Saima Gaines: takes Bryknii rivalry personally, even when unspoken'
            ],
            'languages'        => ['en', 'es'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Cielo Groove is an all-male Latin group in their 30s.',
                'Saima Gaines is an established R&B singer in her early 30s.',
                'The pairing was formed through a mutual respect for music styles.',
                'Cielo Groove is known for their catchy, self-styled "Latin persuasion" music.',
                'Saima was previously associated with heavy autotuning, now looking to establish herself as a bona fide singer.'
            ],
        ],
        [
            'name'             => 'Clémence Duval',
            'subtitle'         => null,
            'genres'           => ['Pop'],
            'song'             => [
                'language' => 'fr',
                'title'    => 'Les Vraies Figures Ne Plient Pas',
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/GRqV3zeffa0',
                ]
            ],
            'traits'           => [
                'an almost too perfect public image',
                'emotionally distant in contrast to her performances',
                'very aware of her appeal as a singer',
                // Suggestions from ChatGPT.
                'carefully controls every aspect of her image',
                'rarely breaks composure in public',
                'treats vulnerability as part of performance rather than reality',
                'privately admires Chelsea\'s purity'
            ],
            'languages'        => ['en', 'fr'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'A French female singer in her mid 20s.',
                'Known for performing cinematic ballads in her native French and occasionally English.',
                'Recognised as one of the industry\'s most beautiful people.',
                'Speaks both English and French.',
                // Suggestions from ChatGPT.
                'Often criticised for being emotionally distant despite lyrical depth.',
                'Has crossover appeal in fashion and film circles.',
                'Some question whether her persona is entirely constructed.'
            ],
        ],
        [
            'name'             => 'Coastliners, the',
            'subtitle'         => null,
            'genres'           => ['Barbershop'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/_3weTrkDw9s',
                    'live' => 'https://youtu.be/Y_fEfA0LkNk',
                ]
            ],
            'traits'           => [
                'does not see any other Act as competition',
                'liberal-minded',
                'generally laid back',
                'family oriented',
                // Suggestion from ChatGPT.
                'quietly amused by the Forty Twos\' intensity'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'West coast style barbershop quartet, ranging from mid 30s to mid 40s.',
                'Music style inspired by The Beach Boys.',
                'Relies solely on vocals and occasional human percussion.'
            ],
        ],
        [
            'name'             => 'Elora James',
            'subtitle'         => null,
            'genres'           => ['Soul'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/gW1kciQOmHE',
                ]
            ],
            'traits'           => [
                'identifies with the Contest theme on an intersectional level',
                'bold and authentic',
                'balanced in her speech',
                'looking to elevate her profile as a result of the Contest'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female soul singer in her mid 30s.',
                'Backed by female singers.',
                'Excels in a capella singing, with minimal instrumentation.'
            ],
        ],
        [
            'name'             => 'Emma Finch',
            'subtitle'         => null,
            'genres'           => ['Americana', 'Folk'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/1P1JR9ih2uM',
                ]
            ],
            'traits'           => [
                'very liberal and left leaning',
                'seeks male approval but is pro-woman',
                'most concerned about bullying toward girls'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female folk singer in her early 30s.',
                'Has been a songwriter since her childhood.',
                'Skilled at playing acoustic guitar and piano.'
            ],
        ],
        [
            'name'             => 'Forty Twos, the',
            'subtitle'         => null,
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/PN9IODfFUag',
                    'live' => 'https://youtu.be/VWquZGkUz_o',
                ]
            ],
            'genres'           => ['Barbershop'],
            'traits'           => [
                'perhaps overly confident about their chances of winning',
                'essentially competing with the Coastliners for dominance',
                'career oriented',
                // Suggestion from ChatGPT.
                'views the Coastliners as outdated competition'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'New York style barbershop quartet, all in their mid 30s.',
                'Believe they destined for (and should be on) Broadway.',
                'Experimental, sometimes including additional instruments and effects in their music.'
            ],
        ],
        [
            'name'             => 'GRMLN',
            'subtitle'         => null,
            'genres'           => ['EDM'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/NeyFskdFEcI',
                ]
            ],
            'traits'           => [
                'hard working and accomplished',
                'aware of social media sentiments and trends',
                'enjoys the party life while knowing his limits',
                'driven to give memorable performances',
                // Suggestions from ChatGPT.
                'balances authenticity with awareness of trends',
                'sometimes questioned for being too calculated',
                'studies BZpeople\'s approach'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male EDM producer and performer in his late teens.',
                'Already highly successful.',
                'In tune with the youth through his music.'
            ],
        ],
        [
            'name'             => 'High School Dropout',
            'subtitle'         => null,
            'genres'           => ['Rock'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/48UnuOmf87w',
                    'live' => 'https://youtu.be/OTdgvXbUpO8',
                ]
            ],
            'traits'           => [
                'takes the F.O.L.D\'s treatment of the MODE Family personally',
                'confidently defiant',
                'motivated to win the Contest through their fans',
                // Suggestions from ChatGPT.
                'feeds off audience energy',
                'leans into controversy when necessary',
                'mutual respect for Violet Riot\'s authenticity'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DOMINANT,
            'is_fan_favourite' => true,
            'notes'            => [
                'A rock band comprising a male lead and three female musicians, all in their mid 20s.',
                'One of CATAWOL Records\' defining Acts.',
                'Has the closest relationship to the MODE Family of all the Acts.'
            ],
        ],
        [
            'name'             => 'Kendra Blaze',
            'subtitle'         => null,
            'genres'           => ['Pop'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/NwSepDk2aM4',
                ]
            ],
            'traits'           => [
                'singing is all, not shy to re-record several times',
                'generally reclusive, only appearing if absolutely necessary',
                'very private and reserved',
                // Suggestions from ChatGPT.
                'perfectionism often delays releases',
                'avoids industry politics entirely',
                'relates to Marissa stepping back into the spotlight'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'Black female pop singer in her late 30s.',
                'Known for her distinctive 80s style.',
                'Influenced by predecessors including Whitney Houston and Donna Summer.'
            ],
        ],
        [
            'name'             => 'Loop Theory',
            'subtitle'         => null,
            'genres'           => ['Hip Hop'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/pCzGsl4Rem0',
                    'live' => 'https://youtu.be/-oL4flOMuD0',
                ]
            ],
            'traits'           => [
                'hyper focused on music and production methods',
                'has a passing interest in female Acts, including Chelsea Chapel and Raya Vibes',
                'does not talk very much',
                // Suggestions from ChatGPT.
                'more comfortable behind the scenes than in the spotlight',
                'respects craft over popularity'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male hip hop producer in his mid 20s.',
                'Inspired by Madlib and J Dilla.',
                'Close to releasing his first album signed to CATAWOL Records.'
            ],
        ],
        [
            'name'             => 'Lorien',
            'subtitle'         => null,
            'genres'           => ['Downtempo', 'EDM'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/Endp8hWGf3E',
                ]
            ],
            'traits'           => [
                'reveals her emotions through her music rather than in interviews',
                'focused on maintaining her public image',
                // Suggestions from ChatGPT.
                'values aesthetic and control',
                'curiosity and slight creative tension with Sonder Drift'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female producer and vocalist in her mid 20s.',
                'Specialises in downtempo and trip hop music.',
                'Known for her bright purple hair and understated yet flamboyant fashion sense.'
            ],
        ],
        [
            'name'             => 'Magenta Men',
            'subtitle'         => null,
            'genres'           => ['Funk', 'Soul'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/rz4G8mixGZY',
                ]
            ],
            'traits'           => [
                'very rarely rehearses their performances',
                'try to outdo each other during performances',
                'an overall respect for each other, despite some rivalry',
                // Suggestions from ChatGPT.
                'secretly believes he is the real star of the duo (each member)',
                'thrives on unpredictability during live performances',
                'uses charm to mask competitiveness',
                'sees Max Bellamy as “one of the few who gets it”'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male retro funk duo in their late 30s.',
                'Heavily influenced by Prince and Morris Day.',
                'Only interested in Acts they deem to be equally or more talented.',
                // Suggestions from ChatGPT.
                'Fans often debate which member is more talented.',
                'Known to improvise sections specifically to outshine the other.',
                'Their lack of rehearsal is seen as either genius or laziness.'
            ],
        ],
        [
            'name'             => 'Marissa Wild',
            'subtitle'         => null,
            'genres'           => ['Pop'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/PdvOFNFjXXc',
                    'live' => 'https://youtu.be/UezSvx7rj3c',
                ]
            ],
            'traits'           => [
                'eager to show the younger Acts how it\'s done',
                'aware her time in the spotlight is over',
                'wants one more opportunity to shine',
                'at peace with her former fame and retirement',
                'respects Kendra\'s discipline'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female pop star in her late 50s.',
                'Formerly one of CATAWOL Records\' most high profile Acts.',
                'Came out of retirement to enter the Contest.'
            ],
        ],
        [
            'name'             => 'Max Bellamy',
            'subtitle'         => 'and The Moonlight Swingers',
            'genres'           => ['Big Band', 'Swing'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/W1C9eyQGUtM',
                ]
            ],
            'traits'           => [
                'focused on optimism and happiness',
                'ambitious for his career',
                'wants to succeed for the Moonlight Swingers as much as for himself',
                'believes in the power of big band and swing music',
                'respects Magenta Men\'s stage presence'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male crooner in his late 30s.',
                'Inspired by Frank Sinatra, Dean Martin and others.',
                'Backed by the Moonlight Swingers: a mixed band of singers and musicians.'
            ],
        ],
        [
            'name'             => 'Miles Everly',
            'subtitle'         => null,
            'genres'           => ['Soft Rock'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/9wqux5Daf7I',
                ]
            ],
            'traits'           => [
                'extremely laid back',
                'does not care about winning the Contest',
                'somewhat liberal minded',
                'not looking to impress anyone',
                // Suggestions from ChatGPT.
                'quietly observant of other Acts',
                'avoids conflict entirely',
                'downplays his own ability',
                'quietly supports Raya\'s optimism'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male acoustic guitarist in his early 40s.',
                'Nostalgic for the 60s and 70s, when soft rock as at its peak.',
                // Suggestions from ChatGPT.
                'Often overlooked in group settings, despite strong musical instincts.',
                'His music tends to grow on listeners over time.',
                'Some Acts underestimate him, to their detriment.'
            ],
        ],
        [
            'name'             => 'Raya Vibes',
            'subtitle'         => null,
            'genres'           => ['Reggae', 'R&B'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/-uiwEfpmMq8',
                ]
            ],
            'traits'           => [
                'generally cheerful and passionate about her performances',
                'rarely expresses anything other than optimism',
                'always looking for her next musical vibe',
                'destined to be a star, but aware of the work involved',
                'enjoys Miles\' calm presence'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female reggae/R&B Act in her early 20s.',
                'From the [Caribbean] islands.'
            ],
        ],
        [
            'name'             => 'RJ "Hound" Mercer',
            'subtitle'         => null,
            'genres'           => ['Blues'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/4wzmAueMIzE',
                    'live' => 'https://youtu.be/TCMSdCqSxGU',
                ]
            ],
            'traits'           => [
                'faithful to his electric guitar',
                'traditional, perhaps old-fashioned',
                'sees the blues as the one authentic genre of music',
                'matter of fact, does not like indirectness',
                "has no tolerance toward musical shortcuts"
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'Experienced male blues musician in his 50s.',
                'Inspired by Bruce Springsteen, Eric Clapton and BB King.'
            ],
        ],
        [
            'name'             => 'Soline Bellefort',
            'subtitle'         => null,
            'genres'           => ['Pop', 'Classical'],
            'song'             => [
                'language' => 'fr',
                'title'    => 'Les Vraies Figures Ne Plient Pas',
                'url'      => [
                    'test' => 'https://youtu.be/mmmz5m5iR78',
                    'live' => 'https://youtu.be/gPL5piSYJLY',
                ]
            ],
            'traits'           => [
                'insists on speaking French, refuses to speak English',
                'effortlessly dominant',
                'very rarely gives interviews',
                'sees the other Acts as beneath her',
                // Suggestions from ChatGPT.
                'expects others to adapt to her, not the other way around',
                'views technical excellence as non-negotiable',
                'unintentionally intimidating presence',
                'dismisses overly produced Acts',
                'has an unspoken admiration for RJ Mercer'
            ],
            'languages'        => ['fr'],
            'rank'             => ActRank::DOMINANT,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female French singer in her mid 30s.',
                'Has a powerful singing voice.',
                'Very successful in her native country, elevated to diva status.',
                // Suggestions from ChatGPT.
                'Has walked out of collaborations that did not meet her standards.',
                'Her refusal to speak English is seen as both principled and elitist.',
                'Often divides audiences between admiration and discomfort.'
            ],
        ],
        [
            'name'             => 'Sonder Drift',
            'subtitle'         => null,
            'genres'           => ['Downtempo', 'EDM'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/h1u2VJN9dvk',
                ]
            ],
            'traits'           => [
                'brother is highly protective of his sister',
                'sister is focused on purity',
                'representing the independent side of music production',
                'socially conscious',
                'their music is a voice for the free-spirited',
                // Suggestions from ChatGPT.
                'values purity and independence',
                'curiosity and slight creative tension with Lorien'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Brother and sister duo in their early 20s.',
                'The male is the elder of the two.',
                'Known for a unique brand of ambient, ethereal and experimental electronic music.',
                'The male specialises in sound engineering and graphics, while the female provides vocals.',
                'Only recently signed to CATAWOL Records after success as an independent Act.'
            ],
        ],
        [
            'name'             => 'SoraNami',
            'subtitle'         => null,
            'genres'           => ['J-Pop'],
            'song'             => [
                'title'    => 'Shin no Jinbutsu Wa Kusshinai',
                'language' => 'ja',
                'url'      => [
                    'test' => 'https://youtu.be/egAJXeP5Mpc',
                    'live' => 'https://youtu.be/zULFHBeWMj8',
                ]
            ],
            'traits'           => [
                'somewhat shy, having has a sheltered upbringing',
                'takes her performances very seriously',
                'cautiously supportive of Airi'
            ],
            'languages'        => ['en', 'ja'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female J-Pop star in her early 20s.',
                'Famous for providing vocals for hit anime series and OVA movies.',
                'Rejected "idol" status to focus on a music career.',
                'Her end goal is to travel the world.'
            ],
        ],
        [
            'name'             => 'Synth & Son',
            'subtitle'         => null,
            'genres'           => ['EDM', 'Pop'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/Uv5H1twkw2Y',
                    'live' => 'https://youtu.be/ihkkqGtFKcM',
                ]
            ],
            'traits'           => [
                'both enjoy the collaboration',
                'sees the Contest as a means to discover potential collaborations with other Acts',
                'hopes to do well in the Contest, win or lose',
                'appreciation of Buck & Jeb\'s spirit'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Father and son duo: father in his late 40s and son in his mid 20s.',
                'Specialising in synth pop, recreating the sounds of the 80s.',
                'Both are talented musicians, enjoying working together.',
                'Have yet to release their first album.'
            ],
        ],
        [
            'name'             => 'Vexon',
            'subtitle'         => null,
            'genres'           => ['EDM'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/vqHHUuoeYrA',
                    'live' => 'https://youtu.be/Ue0D9p4F8uk',
                ]
            ],
            'traits'           => [
                'most of the talking is done through the older members',
                'the youngest is only consulted for soundbites',
                'generally easygoing and calm',
                'lets the music do the talking',
                'somewhat at odds with GRMLN',
                // Suggestions from ChatGPT.
                'carefully constructed group identity',
                'internal hierarchy influences decision-making'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Male trio comprising two college friends in their late teens, and a younger brother in his mid teens.',
                'Inspired by Disclosure',
                'The youngest member wears a plain, nondescript mask as part of their image.',
                'The other two members appear in "purpleface".'
            ],
        ],
        [
            'name'             => 'Violet Riot',
            'subtitle'         => null,
            'genres'           => ['Punk', 'Rock'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/TvDHwelexFk',
                    'live' => 'https://youtu.be/GqzhMIMLVdA',
                ]
            ],
            'traits'           => [
                'extroverted, particularly the lead',
                'confident without being overly aggressive',
                'on the surface, appears to be all about female autonomy',
                'will speak fondly of one or two of the male Acts in the Contest',
                'will show appreciation for other female Acts',
                // Suggestions from ChatGPT.
                'more nuanced than their image suggests',
                'selective about who they respect',
                'mutual respect for High School Dropout\'s authenticity'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::DIVISIVE,
            'is_fan_favourite' => false,
            'notes'            => [
                'All-female punk rock group in their early 20s.',
                'Is entirely staffed and managed by females.',
                'Each member has a distinctive appearance, which somehow works cohesively.'
            ],
        ],
        [
            'name'             => 'Violeta Montenegro',
            'subtitle'         => null,
            'genres'           => ['Latin'],
            'song'             => [
                'url' => [
                    'test' => '',
                    'live' => 'https://youtu.be/ox4EzOJx7kg',
                ]
            ],
            'traits'           => [
                'proud of her genre',
                'in her mind, has something to prove',
                'only respects genuine musicians and singers',
                'more focused on her performance than the essence of the Contest',
                // Suggestions from ChatGPT.
                'openly critical of modern music trends',
                'holds strong opinions on authenticity in music',
                'carries herself like an established legend',
                'sceptical of Cielo Groove, but acknowledges their effort'
            ],
            'languages'        => ['en', 'es'],
            'rank'             => ActRank::WILDCARD,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female Latin/Caribbean singer in her 40s.',
                'Looking to revive classic Latin music as a genre.',
                'Very much against modern production techniques, especially autotune and computer-generated music.',
                // Suggestions from ChatGPT.
                'Frequently clashes ideologically with more modern, production-heavy Acts.',
                'Sees herself as preserving culture rather than competing.',
                'Her stance on "real music" has both supporters and detractors.'
            ],
        ],
        [
            'name'             => 'Westbound',
            'subtitle'         => null,
            'genres'           => ['Pop', 'Rock'],
            'song'             => [
                'url' => [
                    'test' => 'https://youtu.be/aV-SuEhPg9Y',
                    'live' => 'https://youtu.be/4EplOKKiOyU',
                ]
            ],
            'traits'           => [
                'entered the Contest to expand their fan base, particularly internationally',
                'plays on their appeal to female fans',
                'confident they will at least make it to the finals',
                'Bryknii is their only real concern'
            ],
            'languages'        => ['en'],
            'rank'             => ActRank::LOVED,
            'is_fan_favourite' => false,
            'notes'            => [
                'Irish boy band in their early to mid 20s.',
                'Specialising in "teen pop rock", catering to the high school and college crowd.',
                'Grew up together in high school.'
            ],
        ],
    ];
}
