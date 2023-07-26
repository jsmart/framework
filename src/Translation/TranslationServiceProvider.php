<?php

namespace JSmart\Translation;

use JSmart\Foundation\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerLoader();

        $this->app->singleton('translator', function ($app) {

            $trans = new \Illuminate\Translation\Translator($app['translation.loader'], $app['config']['application.locale']);

            $trans->setFallback($app['config']['application.locale_fallback']);

            return $trans;
        });
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new \Illuminate\Translation\FileLoader($app['files'], base_path('application/lang'));
        });
    }
}
