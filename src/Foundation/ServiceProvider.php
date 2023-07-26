<?php

namespace JSmart\Foundation;

use JSmart\Foundation\Application;

abstract class ServiceProvider
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
