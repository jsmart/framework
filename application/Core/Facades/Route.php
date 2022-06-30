<?php

namespace JSmart\Core\Facades;

/**
 * @method static \JSmart\Core\Routing\Router get(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router post(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router put(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router patch(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router delete(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router options(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router any(string $uri, array $action)
 * @method static \JSmart\Core\Routing\Router group($routes)
 * @method static \JSmart\Core\Routing\Router prefix(string $name)
 * @method static \JSmart\Core\Routing\Route args(array|string $name, mixed $default = null)
 * @method static \JSmart\Core\Routing\Route where(array|string $name, string $regex = null, string $default = null)
 * @method static \JSmart\Core\Routing\Route host(string $pattern)
 * @method static \JSmart\Core\Routing\Route middleware(array|string $middleware = [])
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
