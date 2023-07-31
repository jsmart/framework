<?php

namespace JSmart\Routing;

use JSmart\Foundation\Application;
use Illuminate\Http\Request;
use JSmart\Foundation\Pipeline;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    use RouteDependencyResolverTrait;

    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The route collection instance.
     *
     * @var RouteCollection
     */
    protected RouteCollection $routes;

    /**
     * The route group collection instance.
     *
     * @var RouteCollection
     */
    protected RouteCollection $group;

    /**
     * The route group attributes array.
     *
     * @var array
     */
    protected array $attributes;

    /**
     * The route middleware stack.
     *
     * @var array
     */
    protected array $middleware = [];

    /**
     * Create a new Router instance.
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->routes = new RouteCollection;
    }

    /**
     * Dispatch the request to a route and return the response.
     *
     * @param Request $request
     * @return mixed
     */
    public function dispatch(Request $request): mixed
    {
        $this->loadRoutes();

        $controller = $this->makeController($request);

        return $this->sendRequestThroughMiddleware($request, $controller);
    }

    /**
     * Loading routes from config.
     *
     * @return void
     */
    protected function loadRoutes(): void
    {
        (new RoutesRegistrar($this->app, $this))->register();
    }

    /**
     * Set routes.
     *
     * @param RouteCollection $routes
     * @return void
     */
    public function setRouteCollection(RouteCollection $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * Get routes.
     *
     * @return RouteCollection
     */
    public function getRouteCollection(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * Tries to match a request with a set of routes.
     *
     * @param Request $request
     * @return array
     */
    protected function matchRequest(Request $request): array
    {
        $context = new RequestContext();
        $matcher = new UrlMatcher($this->routes, $context->fromRequest($request));

        $match   = $matcher->match($request->getPathInfo());

        $this->app['event.dispatcher']->dispatch([
            'request' => $request, 'match' => $match
        ], 'router.match');

        return $match;
    }

    /**
     * Make the controller instance.
     *
     * @param Request $request
     * @return array
     */
    protected function makeController(Request $request): array
    {
        $match = $this->matchRequest($request);

        $controller = $this->app->make($match['_controller']);

        if (method_exists($controller, 'getMiddleware')) {
            $this->middleware = array_merge($this->middleware, $match['_middleware'], $controller->getMiddleware());
        }

        return [
            'controller'    => $controller,
            'action'        => $match['_action'],
            'args'          => array_diff_key($match, [
                '_controller'   => true,
                '_action'       => true,
                '_middleware'   => true,
                '_route'        => true
            ])
        ];
    }

    /**
     * Send request through the middleware.
     *
     * @param Request $request
     * @param array $controller
     * @return mixed
     */
    protected function sendRequestThroughMiddleware(Request $request, array $controller): mixed
    {
        return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->middleware)
                    ->then($this->runController($controller));
    }

    /**
     * Run the route action and return the response.
     *
     * @param $controller
     * @return mixed
     */
    protected function runController($controller): mixed
    {
        return function($request) use ($controller): mixed {
            return call_user_func_array([$controller['controller'], $controller['action']],
                array_values($this->resolveClassMethodDependencies($controller['args'], $controller['controller'], $controller['action']))
            );
        };
    }

    /**
     * Register a new GET route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function get(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute(['GET', 'HEAD'], $uri, $action, $name);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function post(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function put(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function patch(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function delete(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function options(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute('OPTIONS', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    public function any(string $uri, array $action, string $name = null): Route
    {
        return $this->newRoute(['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $uri, $action);
    }

    /**
     * Create a new Route object.
     *
     * @param array|string $methods
     * @param string $uri
     * @param array $action
     * @param string|null $name
     * @return Route
     */
    protected function newRoute(array|string $methods, string $uri, array $action, string $name = null): Route
    {
        $route = new Route($methods, $uri, $action);

        if (!empty($this->attributes['prefix'])) {
            $this->group->add($route->getName($name, $this->attributes['prefix']), $route);
        }
        else {
            $this->routes->add($route->getName($name), $route);
        }

        return $route;
    }

    /**
     * Create a route group.
     *
     * @param $routes
     * @return void
     */
    public function group($routes)
    {
        $this->group = new RouteCollection();

        if (is_callable($routes)) {
            $routes($this);
        }

        if (isset($this->attributes['prefix'])) {
            $this->group->addPrefix($this->attributes['prefix']);
        }

        if (isset($this->attributes['defaults'])) {
            $this->group->addDefaults($this->attributes['defaults']);
        }

        if (isset($this->attributes['requirements'])) {
            $this->group->addRequirements($this->attributes['requirements']);
        }

        if (isset($this->attributes['host'])) {
            $this->group->setHost($this->attributes['host']);
        }

        foreach ($this->group->all() as $route) {
            $route->setPath(rtrim($route->getPath(), '/'));
        }

        $this->routes->addCollection($this->group);

        $this->attributes = [];
    }

    /**
     * Set a group prefix.
     *
     * @param string $name
     * @return $this
     */
    public function prefix(string $name): self
    {
        $this->attributes['prefix'] = $name;

        return $this;
    }
}
