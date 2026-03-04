<?php

namespace App\Enums;

enum RoundWinState: string
{
    case WINNER = 'win';
    case RUNNER_UP = 'runnerup';
    case NONE = 'none';
}
