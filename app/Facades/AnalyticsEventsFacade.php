<?php

namespace App\Facades;

use App\Services\AnalyticsEvents;
use Illuminate\Support\Facades\Facade;

class AnalyticsEventsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return AnalyticsEvents::class;
    }
}
