<?php
return [
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
        "Return the press release as a JSON object containing:",
        "- title: the headline of the press release,",
        "- content: the body of the press release in Markdown format, using level 2 headings."
    ],
    'round'    => [
        'started' => [
            "Write a 300-400 word press release summarising the start of :round_name in a song contest hosted by :contest_host, called :contest_name.",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win. Use a professional press release tone.",
        ],
        'ended'   => [
            "Write a 300-400 word press release summarising the results of :round_name in a song contest hosted by :contest_host, called :contest_name",
            "Summarise the round with key highlights, surprises, and fan reactions. Mention acts that stood out and any who were tipped to win. Use a professional press release tone.",
        ]
    ]
];
