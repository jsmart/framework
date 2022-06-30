<?php

namespace JSmart\Core\Foundation\Bootstrap;

use JSmart\Core\Foundation\Application;
use JSmart\Core\Facades\Facade;

class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        Facade::setFacadeApplication($app);
    }
}
