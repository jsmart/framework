<?php

namespace JSmart\Routing;

use BadMethodCallException;

abstract class Controller
{
    /**
     * The middleware registered on the controller.
     *
     * @var array
     */
    protected array $middleware = [];

    /**
     * Register middleware on the controller.
     *
     * @param array|string $middleware
     * @return void
     */
    public function middleware(array|string $middleware): void
    {
        if (is_array($middleware)) {
            $this->middleware = array_merge($this->middleware, $middleware);
        }
        else {
            $this->middleware[] = $middleware;
        }
    }

    /**
     * Get the middleware assigned to the controller.
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $parameters): mixed
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
