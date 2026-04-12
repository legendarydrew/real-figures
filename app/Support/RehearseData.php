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
            'description'         => 'Eight rounds with four Acts each, to determine which Songs go through to the finals.',
            'golden_buzzer_perks' => 'Acts will be given a profile and a new promotional image.',
        ],
        [
            'title'               => 'Stage 2: Finals',
            'description'         => 'Qualifying Acts go head-to-head to determine a Grand Winner and three Runners-Up. ' .
                'The winning Song becomes the official anthem of the Contest.',
            'golden_buzzer_perks' => 'Acts will be represented as 3D-printed figures in SilentMode\'s style.',
        ],
    ];

    const array ACTS = [
        [
            'name'             => 'Airi Kisaragi',
            'subtitle'         => null,
            'genres'           => ['J-Pop'],
            'traits'           => [
                'giggly and coy when giving interviews',
                'youthful',
                'energetic'
            ],
            'languages'        => ['ja'],
            'rank'             => ActRank::UNDERDOG,
            'is_fan_favourite' => false,
            'notes'            => [
                'Female J-Pop idol in her mid teens.',
                'Known for her colourful hairstyles outfits.',
                'Stylised high-energy metal music.'
            ],
        ],
        [
            'name'             => 'Axel King',
            'subtitle'         => null,
            'genres'           => ['Blues', 'Rock'],
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
        ['name'             => 'Buck & Jeb',
         'subtitle'         => null,
         'genres'           => ['Country & Western'],
         'traits'           => [
             'generally easygoing',
             'do not take themselves too seriously',
             'derives satisfaction from entertaining the crowd',
             'rely on and compliment each other as a duo',
         ],
         'languages'        => ['en'],
         'rank'             => ActRank::DIVISIVE,
         'is_fan_favourite' => false,
         'notes'            => [
             'Male country and western duo in their 40s.',
             'Competent but known for their comedic take on the genre.'
         ],
        ],
        ['name'             => 'BZpeople',
         'subtitle'         => null,
         'genres'           => ['EDM'],
         'traits'           => [
             'down to earth',
             'results oriented',
             'celebrity is a means to an end',
             'aware he might be an underdog',
             // Suggestions from ChatGPT.
             'quietly confident in his process',
             'measures success differently from mainstream Acts'
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
        ['name'             => 'Chelsea Chapel',
         'subtitle'         => null,
         'genres'           => ['Classical'],
         'traits'           => [
             'quiet confidence',
             'humble but aware of her abilities',
             'noble, complimentary of other Acts'
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
            'traits'           => [
                'Cielo Groove: laid-back, enthusiastic about the pairing and Latin music',
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
            'traits'           => [
                'An almost too perfect public image',
                'Emotionally distant in contrast to her performances',
                'Very aware of her appeal as a singer',
                // Suggestions from ChatGPT.
                'carefully controls every aspect of her image',
                'rarely breaks composure in public',
                'treats vulnerability as part of performance rather than reality'
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
            'traits'           => [
                'Does not see any other Act as competition',
                'Liberal-minded',
                'Generally laid back',
                'Family oriented',
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
            'traits'           => [
                'Identifies with the Contest theme on an intersectional level',
                'Bold and authentic',
                'Balanced in her speech',
                'Looking to elevate her profile as a result of the Contest'
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
            'traits'           => [
                'Very liberal and left leaning',
                'Seeks male approval but is pro-woman',
                'Most concerned about bullying toward girls'
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
            'genres'           => ['Barbershop'],
            'traits'           => [
                'Perhaps overly confident about their chances of winning',
                'Essentially competing with the Coastliners for dominance',
                'Career oriented',
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
            'traits'           => [
                'Hard working and accomplished',
                'Aware of social media sentiments and trends',
                'Enjoys the party life while knowing his limits',
                'Driven to give memorable performances',
                // Suggestions from ChatGPT.
                'balances authenticity with awareness of trends',
                'sometimes questioned for being too calculated'
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
            'traits'           => [
                'Takes the F.O.L.D\'s treatment of the MODE Family personally',
                'Confidently defiant',
                'Motivated to win the Contest through their fans',
                // Suggestions from ChatGPT.
                'feeds off audience energy',
                'leans into controversy when necessary'
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
            'traits'           => [
                'Singing is all, not shy to re-record several times',
                'Generally reclusive, only appearing if absolutely necessary',
                'Very private and reserved',
                // Suggestions from ChatGPT.
                'perfectionism often delays releases',
                'avoids industry politics entirely'
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
            'traits'           => [
                'Hyper focused on music and production methods',
                'Has a passing interest in female Acts, including Chelsea Chapel and Raya Vibes',
                'Does not talk very much',
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
            'traits'           => [
                'Reveals her emotions through her music rather than in interviews',
                'Focused on maintaining her public image',
                // Suggestions from ChatGPT.
                'values aesthetic and control'
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
            'traits'           => [
                'Very rarely rehearses their performances',
                'Try to outdo each other during performances',
                'An overall respect for each other, despite some rivalry',
                // Suggestions from ChatGPT.
                'secretly believes he is the real star of the duo (each member)',
                'thrives on unpredictability during live performances',
                'uses charm to mask competitiveness'
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
            'traits'           => [
                'Eager to show the younger Acts how it\'s done',
                'Aware her time in the spotlight is over',
                'Wants one more opportunity to shine',
                'At peace with her former fame and retirement'
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
            'traits'           => [
                'Focused on optimism and happiness',
                'Ambitious for his career',
                'Wants to succeed for the Moonlight Swingers as much as for himself',
                'Believes in the power of big band and swing music'
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
            'traits'           => [
                'Extremely laid back',
                'Does not care about winning the Contest',
                'Somewhat liberal minded',
                'Not looking to impress anyone',
                // Suggestions from ChatGPT.
                'quietly observant of other Acts',
                'avoids conflict entirely',
                'downplays his own ability'
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
            'traits'           => [
                'Generally cheerful and passionate about her performances',
                'Rarely expresses anything other than optimism',
                'Always looking for her next musical vibe',
                'Destined to be a star, but aware of the work involved'
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
            'traits'           => [
                'Faithful to his electric guitar',
                'Traditional, perhaps old-fashioned',
                'Sees the blues as the one authentic genre of music',
                'Matter of fact, does not like indirectness'
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
            'traits'           => [
                'Insists on speaking French, refuses to speak English',
                'Effortlessly dominant',
                'Very rarely gives interviews',
                'Sees the other Acts as beneath her',
                // Suggestions from ChatGPT.
                'expects others to adapt to her, not the other way around',
                'views technical excellence as non-negotiable',
                'unintentionally intimidating presence'
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
            'traits'           => [
                'Brother is highly protective of his sister',
                'Sister is focused on purity',
                'Representing the independent side of music production',
                'Socially conscious',
                'Their music is a voice for the free-spirited',
                // Suggestions from ChatGPT.
                'values purity and independence'
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
            'traits'           => [
                'Somewhat shy, having has a sheltered upbringing',
                'Takes her performances very seriously'
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
            'traits'           => [
                'Both enjoy the collaboration',
                'Sees the Contest as a means to discover potential collaborations with other Acts',
                'Hopes to do well in the Contest, win or lose'
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
            'traits'           => [
                'Most of the talking is done through the older members',
                'The youngest is only consulted for soundbites',
                'Generally easygoing and calm',
                'Lets the music do the talking',
                'Somewhat at odds with GRMLN',
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
            'traits'           => [
                'Extroverted, particularly the lead',
                'Confident without being overly aggressive',
                'On the surface, appears to be all about female autonomy',
                'Will speak fondly of one or two of the male Acts in the Contest',
                'Will show appreciation for other female Acts',
                // Suggestions from ChatGPT.
                'more nuanced than their image suggests',
                'selective about who they respect'
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
            'traits'           => [
                'Proud of her genre',
                'In her mind, has something to prove',
                'Only respects genuine musicians and singers',
                'More focused on her performance than the essence of the Contest',
                // Suggestions from ChatGPT.
                'openly critical of modern music trends',
                'holds strong opinions on authenticity in music',
                'carries herself like an established legend'
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
            'traits'           => [
                'Entered the Contest to expand their fan base, particularly internationally',
                'Plays on their appeal to female fans',
                'Confident they will at least make it to the finals'
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
