<?php

namespace JSmart\Cookie;

use JSmart\Foundation\ServiceProvider;

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
