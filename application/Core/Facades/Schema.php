<?php

namespace JSmart\Core\Facades;

class Schema extends Facade
{
    /**
     * Get a schema builder instance for a connection.
     *
     * @param string|null $name
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function connection(string|null $name): Builder
    {
        return static::$app['db']->connection($name)->getSchemaBuilder();
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'db.schema';
    }
}
