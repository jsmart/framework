<?php

namespace JSmart\Core\Routing;

use JSmart\Core\Foundation\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('router', function ($app) {
            return new Router($app);
        });
    }
}
