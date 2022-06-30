<?php

namespace JSmart\Core\Foundation\Bootstrap;

use JSmart\Core\Foundation\Application;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;

class HandleExceptions
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        if ($app->config['application.debug']) {
            Debug::enable();
        }
        else {
            ErrorHandler::register();
        }
    }
}
