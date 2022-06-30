<?php

namespace JSmart\Core\Foundation\Bootstrap;

use JSmart\Core\Foundation\Application;

class BootProviders
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $app->bootProviders();
    }
}
