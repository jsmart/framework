<?php

namespace JSmart\Facades;

class Cookie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cookie';
    }
}
