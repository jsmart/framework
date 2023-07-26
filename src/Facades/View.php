<?php

namespace JSmart\Facades;

/**
 * @method static \JSmart\View\View make(string $path, array $data = [], array $mergeData = [])
 * @method static \JSmart\View\View file(string $path, array $data = [], array $mergeData = [])
 * @method static \JSmart\View\View getPath(string $path)
 * @method static \JSmart\View\View setPath(string $path)
 * @method static \JSmart\View\View exists(string $path)
 */

class View extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'view';
    }
}
