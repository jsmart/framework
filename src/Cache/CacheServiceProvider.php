<?php

namespace JSmart\Cache;

use JSmart\Foundation\ServiceProvider;

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
            return new \Illuminate\Cache\CacheManager($app);
        });

        $this->app->singleton('cache.store', function ($app) {
            return $app['cache']->driver();
        });

        $this->app->singleton('memcached.connector', function () {
            return new \Illuminate\Cache\MemcachedConnector;
        });
    }
}
