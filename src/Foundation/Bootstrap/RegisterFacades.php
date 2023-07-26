<?php

namespace JSmart\Foundation\Bootstrap;

use JSmart\Foundation\Application;
use JSmart\Facades\Facade;

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
