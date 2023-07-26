<?php

namespace JSmart\Foundation\Bootstrap;

use JSmart\Foundation\Application;
use JSmart\Validation\ValidationServiceProvider;

class RegisterProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        foreach ($this->defaultProviders() as $provider) {
            $app->register($provider);
        }
    }

    /**
     * Default Service providers.
     *
     * @return array
     */
    private function defaultProviders(): array
    {
        return [
            \JSmart\Cache\CacheServiceProvider::class,
            \JSmart\Cookie\CookieServiceProvider::class,
            \JSmart\Database\DatabaseServiceProvider::class,
            \JSmart\Filesystem\FilesystemServiceProvider::class,
            \JSmart\Mail\MailServiceProvider::class,
            \JSmart\Routing\RouteServiceProvider::class,
            \JSmart\Session\SessionServiceProvider::class,
            \JSmart\Translation\TranslationServiceProvider::class,
            \JSmart\Validation\ValidationServiceProvider::class,
            \JSmart\View\ViewServiceProvider::class,
        ];
    }
}
