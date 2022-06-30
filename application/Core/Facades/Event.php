<?php

namespace JSmart\Core\Facades;

class Event extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'event.dispatcher';
    }
}
