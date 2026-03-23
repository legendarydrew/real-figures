<?php
return [
    'role'     => [
        'You are an in-house journalist at CATAWOL Records, a globally influential record label, charged with writing company press releases. ' .
        'You are encouraged to sensationalise the news surrounding the Contest and the competing Acts, but the public image of CATAWOL Records must be preserved.'
    ],
    'contest'  => [
        'announce'                => [
            "Write a press release summarising the announcement of \":contest_name\": a Song Contest hosted by :contest_host.",
            "The Song Contest is an collaboration between :contest_host and the MODE Family (an independent collective) to raise awareness of adult bullying in hobby spaces.",
            "The following Acts, all signed to :contest_host, are competing in the Song Contest:\n",
            ":acts",
            "\nInclude quotes and statements from a selection of the Acts, based on what you know about them.",
            "Briefly mention any key highlights, surprises, and fan reactions."
        ],
        'running'                 => [
            "Write a press release summarising the current state of the \":contest_name\" Song Contest, hosted by :contest_host.",
            "The following Acts, all signed to :contest_host, are competing in the Song Contest:\n",
            ":acts",
            "\nPay particular attention to the current Round in the current Stage."
        ],
        'over'                    => [
            "Write a press release summarising the outcome \":contest_name\": a Song Contest hosted by :contest_host.",
            "The following Acts had competed in the Song Contest:\n",
            ":acts",
            "\nSummarise the outcome of the Song Contest with key highlights, surprises, and fan reactions. Mention Acts that stood out and any who were tipped to win.",
            "If any donations were made, mention how much was raised as a result of the Song Contest.",
            "If there are any Golden Buzzers, mention which Acts were supported.",
        ],
        'last-stage'              => "This Contest is on the last stage.",
        'golden-buzzers'          => "The following Acts received Golden Buzzers:",
        'donations'               => "A total of :currency :total was raised through the Song Contest.",
        'overall-winners'         => "Overall winner(s):",
        'runners-up'              => "Runner-ups:",
        'previous-stage-winners'  => "Previous stage winners:",
        'current-round'           => "The name of the current Round is \":round_title\".",
        'current-round-competing' => "Competing in this Round:",
    ],
    'stage'    => [
        'ready'              => [
            "Write a press release summarising the anticipation of \":stage_name\" in \":contest_name\": a Song Contest hosted by :contest_host.",
            "If there is more than one Round in this Stage, summarise the random allocation of Acts in each Round, noting any key highlights, surprises, and fan reactions.",
            "Consider any information about the Acts' performances in previous Stages.",
            "None of the Rounds in this Stage have started yet.",
        ],
        'active'             => [
            "Write a press release up to 400 words summarising the current Stage of \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise the current round with key highlights, surprises, and fan reactions.",
            "Consider any information about the Acts' performances in previous Stages."
        ],
        'ended'              => [
            "Write a press release up to 400 words summarising the end of the current Stage of \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise the current Round with key highlights, surprises, and fan reactions.",
            "Mention whether any of the Rounds will have to be judged, which occurs when a Round has no votes.",
            "Consider any information about the Acts' performances in previous Stages."
        ],
        'over'               => [
            "Write a press release up to 400 words summarising the conclusion and results of \":stage_name\" of \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise the outcome of the Stage with key highlights, surprises, and fan reactions.",
        ],
        'stage-acts'         => 'Acts participating in this Stage:',
        'round-breakdown'    => 'The Acts have been split into Rounds in this Stage as follows:',
        'current-round'      => 'The current Round is named :round_title.',
        'current-round-acts' => 'Acts participating in the current Round:',
        'current-round-ends' => 'This Round ends at :round_end.',
        'round-votes'        => 'Number of votes in each Round:',
        'outcome'            => 'The outcomes of the Stage are as follows:',
        'previous-results'   => 'The outcomes of previous Stages were as follows:'
    ],
    'round'    => [
        'started' => [
            "Write a 300-400 word press release summarising the start of a Round called \":round_name\" in \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention Acts that stood out and any who were tipped to win.",
        ],
        'ended'   => [
            "Write a 300-400 word press release summarising the results of a Round called \":round_name\" in \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention Acts that stood out and any who were tipped to win.",
            "If any of the Acts were judged, it means there were no public votes for the Round, and the winners were decided by an independent panel.",
            "Unless an Act has scored no points, do not disclose any of the scores.",
        ],
        "acts" => "Some information about the Acts in this Round:"
    ],
    'act'      => [
        "prompt" => [
            "Write a press release about the following musical Acts, who are taking part in \":contest_name\": a Song Contest hosted by :contest_host.",
            "Summarise each Act's involvement and current activity within the Song Contest, based on the information provided.",
            "Mention any significant details about each Act and their results, using a little creativity at your discretion.",
        ],
        "wins"   => "  Contest wins:"
    ],
    'previous' => [
        // Used when referring to a previous News Post.
        "You are writing the next in a series of press releases covering a Song Contest hosted by :contest_host, called :contest_name.",
        "The previous press release was as follows:\n",
        "---",
        "Headline: :previous_title\n",
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
