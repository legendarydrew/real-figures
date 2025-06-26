<?php
return [
    'round'    => [
        'started' => [
            "Write a 300-400 word press release summarising the start of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
        ],
        'ended'   => [
            "Write a 300-400 word press release summarising the results of a round called \":round_name\" in \":contest_name\": a song contest hosted by :contest_host.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win.",
            "Unless an act has scored no points, do not disclose any of the scores.",
        ]
    ],
    'previous' => [
        // Used when referring to a previous News Post.
        "You are writing a series of press releases covering a song contest hosted by :contest_host, called :contest_name.",
        "The previous press release was as follows:\n",
        "Headline: :previous_title\n",
        ":previous_content\n",
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
