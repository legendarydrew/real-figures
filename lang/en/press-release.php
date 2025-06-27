<?php
return [
    'contest'  => [
        'announce'                => [
            "Write a press release summarising the announcement of \":contest_name\": a song contest hosted by :contest_host.",
            "The song contest is an collaboration between :contest_host and the MODE Family, to raise awareness of adult bullying in hobby spaces.",
            "The following acts, all signed to :contest_host, are competing in the song contest:\n",
            ":acts",
            "\nInclude quotes and statements from a selection of the acts, based on what you know about them.",
            "Briefly mention any key highlights, surprises, and fan reactions."
        ],
        'running'                 => [
            "Write a press release summarising the current state of the \":contest_name\" song contest, hosted by :contest_host.",
            "The following acts, all signed to :contest_host, are competing in the song contest:\n",
            ":acts",
            "\nPay particular attention to the current round in the current stage."
        ],
        'over'                    => [
            "Write a press release summarising the outcome \":contest_name\": a song contest hosted by :contest_host.",
            "The following acts had competed in the song contest:\n",
            ":acts",
            "\nSummarise the outcome song contest with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
            "If any donations were made, mention how much was raised as a result of the song contest.",
            "If there are any Golden Buzzers, mention which acts were supported.",
        ],
        'last-stage'              => "This contest is on the last stage.",
        'golden-buzzers'          => "The following acts received Golden Buzzers:",
        'donations'               => "A total of :currency :total was raised through the song contest.",
        'overall-winners'         => "Overall winner(s):",
        'runners-up'              => "Runner-ups:",
        'previous-stage-winners'  => "Previous stage winners:",
        'current-round'           => "Current round: :round_title",
        'current-round-competing' => "Competing in this round",
    ],
    'round'    => [
        'started' => [
            "Write a 300-400 word press release summarising the start of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
        ],
        'over' => [
            "Write a 300-400 word press release summarising the results of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
            "Unless an act has scored no points, do not disclose any of the scores.",
        ]
    ],
    'previous' => [
        // Used when referring to a previous News Post.
        "You are writing a series of press releases covering a song contest hosted by :contest_host, called :contest_name.",
        "The previous press release was as follows:\n",
        "---",
        "Headline: :previous_title\n",
        "Content:",
        ":previous_content\n",
        "---",
        "Using the previous press release, write a follow-up release, consistent in style and tone, including these updates:\n"
    ],
    'output'   => [
        // Parameters for the press release output.
        "Use a professional press release tone.",
        "Return the press release as a JSON object containing:",
        "- title: the headline of the press release,",
        "- content: the body of the press release in Markdown format, using level 2 headings."
    ],
];
