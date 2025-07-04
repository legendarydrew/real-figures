<?php

namespace App\Enums;

enum NewsPostType: string
{
    case CUSTOM_POST_TYPE = 'custom';
    case CONTEST_POST_TYPE = 'contest';
    case STAGE_POST_TYPE = 'stage';
    case ROUND_POST_TYPE = 'round';
    case ACT_POST_TYPE = 'act';
}
