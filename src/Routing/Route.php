<?php

namespace JSmart\Routing;

use Illuminate\Support\Arr;
use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute
{
    public string $name;

    /**
     * Create a new Route instance.
     *
     * @param array|string $methods
     * @param string $path
     * @param array $action
     * @return void
     */
    public function __construct(array|string $methods, string $path, array $action)
    {
        $defaults = [
            '_controller'   => $action[0] ?? null,
            '_action'       => $action[1] ?? '__invoke',
            '_middleware'   => [],
        ];

        parent::__construct($path, $defaults, [], [], null, [], $methods);
    }

    /**
     * Set arguments on the route.
     *
     * @param array|string $name
     * @param mixed|null $default
     * @return $this
     */
    public function args(array|string $name, mixed $default = null): self
    {
        if (is_array($name)) {
            return $this->addDefaults($name);
        }

        return $this->setDefault($name, $default);
    }

    /**
     * Set regular expression requirement on the route.
     *
     * @param array|string $name
     * @param string|null $regex
     * @param string|null $default
     * @return $this
     */
    public function where(array|string $name, string $regex = null, string $default = null): self
    {
        if (is_array($name)) {
            return $this->addRequirements($name);
        }

        if ($default) {
            $this->setDefault($name, $default);
        }

        return $this->setRequirement($name, $regex);
    }

    /**
     * Set host on the route.
     *
     * @param string $pattern
     * @return $this
     */
    public function host(string $pattern): self
    {
        return $this->setHost($pattern);
    }

    /**
     * Set middleware on the route.
     *
     * @param array|string $middleware
     * @return $this
     */
    public function middleware(array|string $middleware = []): self
    {
        if (is_string($middleware)) {
            $middleware = [$middleware];
        }

        return $this->setDefault('_middleware', $middleware);
    }

    /**
     * Generated route name.
     *
     * @param string|null $name
     * @param string|null $prefix
     * @return string
     */
    public function getName(string $name = null, string $prefix = null): string
    {
        if ($name) {
            return ($prefix ? trim($prefix, '/') . '/' : '') . $name;
        }

        return trim(Arr::last($this->getMethods()) . ($prefix ? '/' . $prefix : '') . $this->getPath(), '/');
    }
}
