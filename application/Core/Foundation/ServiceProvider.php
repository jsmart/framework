<?php

namespace JSmart\Core\Foundation;

use JSmart\Core\Foundation\Application;

abstract class ServiceProvider
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
