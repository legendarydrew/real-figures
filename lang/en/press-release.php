<?php

return [
    'prompt' => [
        'system' => <<<PROMPT
You are a professional press officer for CATAWOL Records, covering a Song Contest raising awareness of adult bullying in hobby spaces.

Your role is to write compelling, polished press releases based on structured input.

General rules:
- Always include a strong, attention-grabbing headline
- Open with a clear summary (who, what, why it matters)
- Match tone to the press release type
- Use vivid but controlled language (avoid fluff)
- Include quotes when provided or generate one if missing
- End with a clear call to action
- Capitalise references to the Contest, Stages, Rounds and Acts

Press release types and styles:

General:
- Promotional with a formal tone
- Emphasise CATAWOL Records' philanthropy and goodwill

Contest:
- Big, exciting, promotional
- Focus on scale and participation

Stage:
- Build anticipation for the outcome after voting
- Emphasise Acts favoured to win

Round:
- Urgent and time-sensitive if the Round is active
- Focus on potential rivalries

Results:
- Dramatic and celebratory
- Emphasise winners and impact
- Do not mention scores or vote counts unless the Contest has ended

Act:
- Personal and narrative
- Explore background and artistic identity
- Mention their connection to the Contest and it's theme
- Do not mention exact scores, unless they have 0 points

Return JSON with:
- title
- content (Markdown format with level 2 headings).
PROMPT,

        'begin' => <<<PROMPT
Generate a press release using the provided data.

Maintain consistency with the tone and style of previous press releases where relevant.

Previous press releases:
:history

New data:
:data
PROMPT,

        'retry' => <<<PROMPT
Your previous response was invalid or did not match the required JSON format.

You MUST:
- Return valid JSON only
- Include exactly these fields: title, content
- Ensure all fields are strings
- Do not include any extra text outside JSON

Maintain consistency with these previous press releases:

:historyText

Retry using this data:

:data
PROMPT,
    ],

    'contest' => [
        'information' => <<<INFO

About the Contest:

CATAWOL Records is hosting Real Figures Don't F.O.L.D: a Song Contest featuring Acts signed to the label, to raise awareness of adult bullying in hobby spaces.
The Song Contest is a collaboration between CATAWOL Records and an independent group called the MODE Family, which has been under attack by a group known as the F.O.L.D.
The Contest aims to raise money for charity, particularly those involved in addressing bullying and mental health issues.
INFO,
    ],
    'act'     => [
        'prefix'    => 'Act information:',
        'genres'    => '- Genres: :genres',
        'languages' => '- Languages spoken: :languages',
        'members'   => '- Members:',
        'traits'    => '- Traits:',
        'notes'     => '- Notes:',
        'profile'   => '- Profile:',
        'highlight' => 'For :name:',
        'outcomes'  => [
            'heading' => "Outcome of Rounds they were involved in:",
            'round'   => "  :round",
            'result'  => "    :name scored :score point(s)"
        ],
        "accolades" => [
            "winner"    => "  Winner in :round",
            "runner-up" => "  Runner-up in :round",
        ],
        "buzzers"   => "  Was awarded :count Golden Buzzer(s) in :stage."
    ],
    'results' => [
        'title'      => 'Real Figures Don\'t F.O.L.D - Results',
        'stage'      => [
            'ended'    => ':name is over.',
            'started'  => ':name is currently running.',
            'inactive' => ':name has yet to start.'
        ],
        'favourites' => [
            'heading' => 'Favourites to win:',
            'name'    => '  :name'
        ],
        'outcomes'   => [
            'heading' => "Results of :name:",
            'round'   => "  :round",
            'result'  => "    :name - :score point(s) from :votes votes",
            'manual'  => "  (decided by an independent panel)"
        ]
    ],
    'stage'   => [
        'title'       => 'About :stage',
        'description' => [
            'title'        => 'A description of :stage:',
            'buzzer-perks' => 'Golden Buzzer perks: :perks',
            'first-stage'  => 'Acts were allocated to Rounds at random.',
            'last-stage'   => 'This is the last Stage, which is expected to have ten Acts.'
        ],
        'highlight'   => [
            'not-started'       => 'The Stage has not yet started.',
            'started'       => 'The Stage has started.',
            'ended'       => 'The Stage has ended.',
            'manual-vote' => 'No votes were cast, so an independent panel will judge.',
            'acts'        => 'Act information:',
            'favourites'  => 'Favourites to win:',
            'no-favourites'  => 'No Acts are favourites to win.'
        ]
    ]
];
