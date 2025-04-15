<?php

namespace App\Facades;

use App\Services\PayPalService;
use Illuminate\Support\Facades\Facade;

class PaypalServiceFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return PayPalService::class;
    }
}
