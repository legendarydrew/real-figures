<?php

namespace App\Facades;

use App\Services\Contest;
use Illuminate\Support\Facades\Facade;

class ContestFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return Contest::class;
    }
}
