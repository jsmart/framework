<?php

namespace JSmart\Core\Foundation\Bootstrap;

use JSmart\Core\Foundation\Application;
use JSmart\Core\Validation\ValidationServiceProvider;

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
            \JSmart\Core\Cache\CacheServiceProvider::class,
            \JSmart\Core\Cookie\CookieServiceProvider::class,
            \JSmart\Core\Database\DatabaseServiceProvider::class,
            \JSmart\Core\Filesystem\FilesystemServiceProvider::class,
            \JSmart\Core\Mail\MailServiceProvider::class,
            \JSmart\Core\Routing\RouteServiceProvider::class,
            \JSmart\Core\Session\SessionServiceProvider::class,
            \JSmart\Core\Validation\ValidationServiceProvider::class,
            \JSmart\Core\View\ViewServiceProvider::class,
        ];
    }
}
