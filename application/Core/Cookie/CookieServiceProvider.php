<?php

namespace JSmart\Core\Cookie;

use JSmart\Core\Foundation\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('cookie', function ($app) {
            //return new Cookie();
        });
    }
}
