<?php

namespace JSmart\Core\Filesystem;

use Exception;

class Filesystem
{
    /**
     * Require the given file.
     *
     * @param string $path
     * @param array $data
     * @return mixed
     *
     * @throws Exception
     */
    public function getRequire(string $path, array $data = []): mixed
    {
        if (is_file($path)) {
            $__path = $path;
            $__data = $data;

            return (static function () use ($__path, $__data) {
                extract($__data, EXTR_SKIP);

                return require $__path;
            })();
        }

        throw new Exception("File does not exist at path {$path}.");
    }

    /**
     * Require the given file once.
     *
     * @param string $path
     * @param array $data
     * @return mixed
     *
     * @throws Exception
     */
    public function requireOnce(string $path, array $data = []): mixed
    {
        if (is_file($path)) {
            $__path = $path;
            $__data = $data;

            return (static function () use ($__path, $__data) {
                extract($__data, EXTR_SKIP);

                return require_once $__path;
            })();
        }

        throw new Exception("File does not exist at path {$path}.");
    }
}
