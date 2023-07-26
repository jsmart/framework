<?php

namespace JSmart\Events;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Dispatcher implements \Illuminate\Contracts\Events\Dispatcher
{
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string $events
     * @param callable|array $listener
     * @param int $priority
     * @return void
     */
    public function listen($events, $listener = null, int $priority = 0)
    {
        $this->dispatcher->addListener($events, $listener, $priority);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName
     * @return bool
     */
    public function hasListeners($eventName): bool
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    /**
     * Adds an event subscriber.
     *
     * @param object|string $subscriber
     * @return void
     */
    public function subscribe($subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param array|object $event
     * @param mixed $payload
     * @param bool $halt
     * @return object
     */
    public function dispatch($event, $payload = null, $halt = false): object
    {
        if (!is_string($payload)) {
            $payload = null;
        }

        return $this->dispatcher->dispatch($event, $payload);
    }

    /**
     * {@inheritdoc}
     */
    public function until($event, $payload = []) {}

    /**
     * {@inheritdoc}
     */
    public function push($event, $payload = []) {}

    /**
     * {@inheritdoc}
     */
    public function flush($event) {}

    /**
     * {@inheritdoc}
     */
    public function forget($event) {}

    /**
     * {@inheritdoc}
     */
    public function forgetPushed() {}
}
