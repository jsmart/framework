<?php

namespace JSmart\Core\Session;

use JSmart\Core\Foundation\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('session', function ($app) {
            //return new Session();
        });
    }
}
