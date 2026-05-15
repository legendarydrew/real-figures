<?php

namespace App\Enums;

enum VoteType: string
{
    case ORGANIC = "O";    // A vote cast by a site visitor.
    case MANUAL = "M";     // A vote cast by the "independent panel".
    case DUMBRICK = "D";   // A vote cast from Dumbrick data.
}
