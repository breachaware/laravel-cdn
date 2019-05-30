<?php

namespace BreachAware\LaravelCdn\Facades;

use Illuminate\Support\Facades\Facade;

class CdnFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'CDN';
    }
}
