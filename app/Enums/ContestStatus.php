<?php

namespace App\Enums;

enum ContestStatus: string
{
    case COMING_SOON = 'coming-soon';
    case COUNTDOWN = 'countdown'; // to the beginning of a Stage.
    case ACTIVE = 'active';       // a Round is currently active.
    case JUDGEMENT = 'judgement'; // Stage has ended, winners have to be chosen.
    case OVER = 'over';           // the Contest is over.
}
