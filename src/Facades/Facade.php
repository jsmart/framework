<?php

namespace JSmart\Facades;

use JSmart\Foundation\Application;

abstract class Facade
{
    /**
     * The application instance being facaded.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Set the application instance.
     *
     * @param Application $app
     * @return void
     */
    public static function setFacadeApplication(Application $app): void
    {
        static::$app = $app;
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return Application
     */
    public static function getFacadeApplication(): Application
    {
        return static::$app;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     *
     * @throws RuntimeException
     */
    public static function __callStatic(string $method, array $arguments = []): mixed
    {
        $instance = self::getFacadeApplication()->make(static::getFacadeAccessor());

        if (!$instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$arguments);
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        throw new \RuntimeException('Facade does not implement getFacadeAccessor method.');
    }
}
