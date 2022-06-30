<?php

namespace JSmart\Core\Filesystem;

use JSmart\Core\Foundation\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('files', function ($app) {
            return new Filesystem();
        });
    }
}
