<?php

namespace JSmart\Log\Events;

class MessageLogged
{
    public string $level;
    public string $message;
    public array $context;

    /**
     * Create a new event instance.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function __construct(string $level, string $message, array $context = [])
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
    }
}
