<?php

namespace JSmart\Foundation\Bootstrap;

use JSmart\Foundation\Application;

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
