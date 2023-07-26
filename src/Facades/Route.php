<?php

namespace JSmart\Facades;

/**
 * @method static \JSmart\Routing\Router get(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router post(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router put(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router patch(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router delete(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router options(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router any(string $uri, array $action, string $name = null)
 * @method static \JSmart\Routing\Router group($routes)
 * @method static \JSmart\Routing\Router prefix(string $name)
 * @method static \JSmart\Routing\Route args(array|string $name, mixed $default = null)
 * @method static \JSmart\Routing\Route where(array|string $name, string $regex = null, string $default = null)
 * @method static \JSmart\Routing\Route host(string $pattern)
 * @method static \JSmart\Routing\Route middleware(array|string $middleware = [])
 */

class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'router';
    }
}
