<?php

namespace JSmart\Validation;

use Illuminate\Http\Request;
use JSmart\Foundation\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerPresenceVerifier();
        $this->registerValidationFactory();
        $this->registerRequestValidation();
    }

    /**
     * Register the validation factory.
     *
     * @return void
     */
    protected function registerValidationFactory(): void
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new \Illuminate\Validation\Factory($app['translator'], $app);

            if (isset($app['db'], $app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
    }

    /**
     * Register the database presence verifier.
     *
     * @return void
     */
    protected function registerPresenceVerifier(): void
    {
        $this->app->singleton('validation.presence', function ($app) {
            return new \Illuminate\Validation\DatabasePresenceVerifier($app['db']);
        });
    }

    /**
     * Register the "validate" macro on the request.
     *
     * @return void
     */
    public function registerRequestValidation(): void
    {
        Request::macro('validate', function (array $rules, ...$params) {
            return validator()->validate($this->all(), $rules, ...$params);
        });

        Request::macro('validateWithBag', function (string $errorBag, array $rules, ...$params) {
            try {
                return $this->validate($rules, ...$params);
            } catch (\Illuminate\Validation\ValidationException $e) {
                $e->errorBag = $errorBag;

                throw $e;
            }
        });
    }
}
