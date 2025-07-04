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
        'current-round'           => "The name of the current round is \":round_title\".",
        'current-round-competing' => "Competing in this round:",
    ],
    'stage'    => [
        'ready'              => [
            "Write a press release summarising the anticipation of \":stage_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "If there is more than one round in this stage, summarise the random allocation of acts in each round, noting any key highlights, surprises, and fan reactions.",
            "Consider any information about the acts' performances in previous stages.",
            "None of the rounds in this stage have started yet.",
        ],
        'active'             => [
            "Write a press release up to 400 words summarising the current stage of \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the current round with key highlights, surprises, and fan reactions.",
            "Consider any information about the acts' performances in previous stages."
        ],
        'ended'              => [
            "Write a press release up to 400 words summarising the current stage of \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the current round with key highlights, surprises, and fan reactions.",
            "Mention whether any of the rounds will have to be judged, which occurs when a round has no votes.",
            "Consider any information about the acts' performances in previous stages."
        ],
        'over'               => [
            ""
        ],
        'stage-acts'         => 'Acts participating in this stage:',
        'round-breakdown'    => 'The acts have been split into rounds in this stage as follows:',
        'current-round'      => 'The current round is named :round_title.',
        'current-round-acts' => 'Acts participating in the current round:',
        'current-round-ends' => 'This round ends at :round_end.',
        'round-votes'        => 'Number of votes in each round:',
        'outcome'            => 'The outcomes of the stage are as follows:',
        'previous-results'   => 'The outcomes of previous stages were as follows:'
    ],
    'round'    => [
        'started' => [
            "Write a 300-400 word press release summarising the start of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
        ],
        'ended' => [
            "Write a 300-400 word press release summarising the results of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
            "Unless an act has scored no points, do not disclose any of the scores.",
        ]
    ],
    'act' => [
        "prompt" => [
            "Write a press release about the following musical acts, who are taking part in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise each act's involvement and current activity within the song contest, based on the information provided.",
            "Mention any significant details about each act and their results, using a little creativity.",
        ],
        "wins"   => "  Contest wins:"
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
        "Using the previous press release, write a follow-up release, consistent in style and tone, with these parameters:\n"
    ],
    'output'   => [
        // Parameters for the press release output.
        "Use a professional press release tone.",
        "Return the press release as a JSON object containing:",
        "- title: the headline of the press release,",
        "- content: the body of the press release in Markdown format, using level 2 headings."
    ],
];
