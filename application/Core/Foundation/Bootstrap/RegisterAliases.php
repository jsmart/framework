<?php

namespace JSmart\Core\Foundation\Bootstrap;

use JSmart\Core\Foundation\Application;

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
     * Core class aliases in the container.
     *
     * @return array
     */
    private function coreContainerAliases(): array
    {
        return [
            'app' => [self::class, \JSmart\Core\Foundation\Application::class],
            //'request' => [\JSmart\Core\Foundation\Http\Request::class, \Symfony\Component\HttpFoundation\Request::class],
            //'view' => [\JSmart\Core\View\View::class],
        ];
    }
}
