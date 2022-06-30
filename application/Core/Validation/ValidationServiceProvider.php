<?php

namespace JSmart\Core\Validation;

use JSmart\Core\Foundation\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('validator', function ($app) {
            return new Validator($app);
        });
    }
}
