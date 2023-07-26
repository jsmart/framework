<?php

namespace JSmart\Foundation\Bootstrap;

use JSmart\Foundation\Application;

class RegisterAliases
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        foreach ($this->coreContainerAliases() as $key => $aliases) {
            foreach ($aliases as $alias) {
                $app->alias($key, $alias);
            }
        }
    }

    /**
     * core class aliases in the container.
     *
     * @return array
     */
    private function coreContainerAliases(): array
    {
        return [
            'app' => [self::class, \JSmart\Foundation\Application::class],

            'files' => [\Illuminate\Filesystem\Filesystem::class],
            'filesystem' => [\Illuminate\Filesystem\FilesystemManager::class, \Illuminate\Contracts\Filesystem\Factory::class],
            'filesystem.disk' => [\Illuminate\Contracts\Filesystem\Filesystem::class],
            'filesystem.cloud' => [\Illuminate\Contracts\Filesystem\Cloud::class],

            'request' => [\JSmart\Http\Request::class, \Symfony\Component\HttpFoundation\Request::class],
            'session' => [\Illuminate\Session\SessionManager::class],
            //'session.store' => [\Illuminate\Session\Store::class, \Illuminate\Contracts\Session\Session::class],
            //'validator' => [\Illuminate\Validation\Factory::class, \Illuminate\Contracts\Validation\Factory::class],
            //'view' => [\JSmart\View\View::class],
        ];
    }
}
