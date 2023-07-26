<?php

namespace JSmart\Foundation\Bootstrap;

use JSmart\Foundation\Application;

class RegisterListeners
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        foreach ($app->config->get('listeners', []) as $event => $listeners) {
            foreach ($listeners as $listener) {
                $app->get('event.dispatcher')->addListener($event, new $listener);
            }
        }
    }
}
