<?php

namespace App\Enums;

// As suggested by ChatGPT.
enum ActRank: int
{
    case DOMINANT = 0;  // An Act with a very large following.
    case LOVED = 1;     // An Act loved by their fans.
    case DIVISIVE = 2;  // An Act that splits public opinion.
    case UNDERDOG = 3;  // An Act that isn't expected to win.
    case WILDCARD = 4;  // An Act that isn't widely known.
}
