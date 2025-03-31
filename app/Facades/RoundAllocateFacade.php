<?php

namespace App\Facades;

use App\Services\RoundAllocate;
use Illuminate\Support\Facades\Facade;

class RoundAllocateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return RoundAllocate::class;
    }
}
