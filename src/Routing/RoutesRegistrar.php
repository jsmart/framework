<?php

namespace JSmart\Routing;

use JSmart\Foundation\Application;
use JSmart\Facades\Cache;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Finder\Finder;

class RoutesRegistrar
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The router instance.
     *
     * @var Router
     */
    protected Router $router;

    /**
     * Create a new route file registrar instance.
     *
     * @param Router $router
     */
    public function __construct(Application $app, Router $router)
    {
        $this->app = $app;

        $this->router = $router;
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function register(): void
    {
        $routes = Cache::store('system')->get('RouteCollection');

        if ($routes instanceof RouteCollection && empty($this->app->config['application.debug'])) {
            $this->router->setRouteCollection($routes);
        }
        else {
            $this->findRoutes();
        }
    }

    /**
     * Require the given routes files.
     *
     * @return void
     */
    private function findRoutes(): void
    {
        foreach (Finder::create()->files()->name('Routes.php')->in($this->app->modulesPath('*/config')) as $file) {
            require_once $file->getRealPath();
        }

        if (empty($this->app->config['application.debug'])) {
            Cache::store('system')->put('RouteCollection', $this->router->getRouteCollection());
        }
    }
}
