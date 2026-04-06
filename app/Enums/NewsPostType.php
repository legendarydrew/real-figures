<?php

namespace App\Enums;

enum NewsPostType: string
{
    case GENERAL = 'general';
    case CONTEST = 'contest';
    case STAGE = 'stage';
    case ROUND = 'round';
    case ACT = 'act';
    case RESULTS = 'results';
}
