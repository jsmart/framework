<?php

namespace JSmart\Core\Events;

class DispatchObject
{
    public function __construct(array $event = [])
    {
        foreach ($event as $name => $value) {
            $this->$name = $value;
        }
    }
}
