<?php

namespace Fantismic\DynSettings\Facades;

use Illuminate\Support\Facades\Facade;

class DynSettings extends Facade
{

    /**
    * Get the registered name of the component.
    *
    * @return string
    */
    protected static function getFacadeAccessor() { return 'DynSettings'; }
}