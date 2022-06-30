<?php

namespace JSmart\Core\Log;

use JSmart\Core\Foundation\ServiceProvider;

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
            return new LogManager($app);
        });
    }
}
