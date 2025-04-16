<?php

namespace App\Facades;

use App\Services\PaypalService;
use Illuminate\Support\Facades\Facade;

class PaypalServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return PaypalService::class;
    }
}
