<?php

namespace JSmart\Log;

use JSmart\Foundation\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('log', function ($app) {
            return new Logger($app);
        });
    }
}
