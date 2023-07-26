<?php

namespace JSmart\Events;

use JSmart\Foundation\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('event.dispatcher', function ($app) {
            return new EventDispatcher();
        });

        $this->app->singleton('events', function ($app) {
            return new Dispatcher($app->get('event.dispatcher'));
        });
    }
}
