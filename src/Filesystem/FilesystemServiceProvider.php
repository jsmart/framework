<?php

namespace JSmart\Filesystem;

use JSmart\Foundation\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem();
        });

        $this->app->singleton('filesystem', function ($app) {
            return new \Illuminate\Filesystem\FilesystemManager($app);
        });

        $this->app->singleton('filesystem.disk', function ($app) {
            return $app['filesystem']->disk($this->getDefaultDriver());
        });

        $this->app->singleton('filesystem.cloud', function ($app) {
            return $app['filesystem']->disk($this->getCloudDriver());
        });
    }

    /**
     * Get the default file driver.
     *
     * @return string
     */
    protected function getDefaultDriver(): string
    {
        return $this->app['config']['filesystems.default'];
    }

    /**
     * Get the default cloud based file driver.
     *
     * @return string
     */
    protected function getCloudDriver(): string
    {
        return $this->app['config']['filesystems.cloud'];
    }
}
