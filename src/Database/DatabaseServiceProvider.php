<?php

namespace JSmart\Database;

use JSmart\Foundation\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Model::setConnectionResolver($this->app['db']);
        \Illuminate\Database\Eloquent\Model::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        Model::clearBootedModels();

        $this->registerConnectionServices();
    }

    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices(): void
    {
        $this->app->singleton('db.factory', function ($app) {
            return new \Illuminate\Database\Connectors\ConnectionFactory($app);
        });

        $this->app->singleton('db', function ($app) {
            return new \Illuminate\Database\DatabaseManager($app, $app['db.factory']);
        });

        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->app->bind('db.schema', function ($app) {
            return $app['db']->connection()->getSchemaBuilder();
        });

        $this->app->singleton('db.transactions', function ($app) {
            return new \Illuminate\Database\DatabaseTransactionsManager;
        });
    }
}
