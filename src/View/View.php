<?php

namespace JSmart\View;

use JSmart\Foundation\Application;

class View
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The path to the view file.
     *
     * @var string
     */
    protected string $path;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $path
     * @param array $data
     * @param array $mergeData
     * @return string
     */
    public function make(string $path, array $data = [], array $mergeData = []): string
    {
        return $this->file($path, $data, $mergeData);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param string $path
     * @param array $data
     * @param array $mergeData
     * @return string
     */
    public function file(string $path, array $data = [], array $mergeData = []): string
    {
        $obLevel = ob_get_level();

        ob_start();

        try {
            $this->app['files']->getRequire(base_path($this->getPath($path)), array_merge($data, $mergeData));
        }
        catch (Throwable $e){
            while (ob_get_level() > $obLevel) {
                ob_end_clean();
            }

            throw $e;
        }

        return ltrim(ob_get_clean());
    }

    /**
     * Get the path to the view file.
     *
     * @param string $path
     * @return string
     */
    public function getPath(string $path = ''): string
    {
        return isset($this->path) ? $this->path . DIRECTORY_SEPARATOR . $path : $path;
    }

    /**
     * Set the path to the view.
     *
     * @param string $path
     * @return void
     */
    public function setPath(string $path): void
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * Determine if a file or directory exists.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($this->getPath($path));
    }
}
