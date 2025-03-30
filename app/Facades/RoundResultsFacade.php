<?php

namespace App\Facades;

use App\Services\RoundResults;
use Illuminate\Support\Facades\Facade;

class RoundResultsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return RoundResults::class;
    }
}
