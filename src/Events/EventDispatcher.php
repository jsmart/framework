<?php

namespace JSmart\Events;

use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;

class EventDispatcher extends SymfonyEventDispatcher
{
    /**
     * {@inheritdoc}
     */
    public function dispatch(array|object $event, string $eventName = null): object
    {
        if (is_array($event)) {
            $event = new DispatchObject($event);
        }

        return parent::dispatch($event, $eventName);
    }
}
