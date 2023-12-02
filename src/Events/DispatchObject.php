<?php

namespace JSmart\Events;

class DispatchObject
{
    private array $properties = [];

    public function __construct(array $event = [])
    {
        foreach ($event as $name => $value) {
            $this->properties[$name] = $value;
        }
    }

    public function __get($name)
    {
        return $this->properties[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }
}
