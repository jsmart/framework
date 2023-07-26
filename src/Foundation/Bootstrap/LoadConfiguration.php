<?php

namespace JSmart\Foundation\Bootstrap;

use Exception;
use JSmart\Foundation\Application;
use JSmart\Config\Repository;
use Symfony\Component\Finder\Finder;

class LoadConfiguration
{
    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     * @throws Exception
     */
    public function bootstrap(Application $app): void
    {
        $items = [];

        $app->instance('config', $config = new Repository($items));

        $this->loadConfigurationFiles($app, $config);

        date_default_timezone_set($config->get('application.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param Application $app
     * @param Repository $repository
     * @return void
     * @throws Exception
     */
    protected function loadConfigurationFiles(Application $app, Repository $repository): void
    {
        $files = $this->getConfigurationFiles($app);

        if (!isset($files['application'])) {
            throw new Exception('Unable to load the "application" configuration file.');
        }

        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    /**
     * Get all of the configuration files for the application.
     *
     * @param Application $app
     * @return array
     */
    protected function getConfigurationFiles(Application $app): array
    {
        $files = [];

        foreach (Finder::create()->files()->name('*.php')->in($app->configPath()) as $file) {
            $files[mb_strtolower(basename($file->getRealPath(), '.php'))] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }
}
