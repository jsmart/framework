<?php

namespace JSmart\Session;

use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\SessionManager;
use JSmart\Foundation\ServiceProvider;

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
            return new SessionManager($app);
        });

        $this->app->singleton('session.store', function ($app) {
            return $app->make('session')->driver();
        });

        $this->app->singleton(StartSession::class, function ($app) {
            return new StartSession($app->make(SessionManager::class), function () use ($app) {
                return $app->make(CacheFactory::class);
            });
        });
    }
}
