<?php

namespace JSmart\View;

use JSmart\Foundation\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('view', function ($app) {
            return new View($app);
        });
    }
}
