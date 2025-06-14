<?php

namespace App\Facades;

use App\Services\VoteBreakdown;
use Illuminate\Support\Facades\Facade;

class VoteBreakdownFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return VoteBreakdown::class;
    }
}
