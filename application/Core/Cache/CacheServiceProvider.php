<?php

namespace JSmart\Core\Cache;

use JSmart\Core\Foundation\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('cache', function ($app) {
            //return new Cache();
        });
    }
}
