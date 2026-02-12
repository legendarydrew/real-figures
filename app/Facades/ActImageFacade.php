<?php

namespace App\Facades;

use App\Services\ActImage;
use Illuminate\Support\Facades\Facade;

class ActImageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return ActImage::class;
    }
}
