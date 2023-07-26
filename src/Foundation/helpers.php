<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Factory;

if (!file_exists('abort'))
{
    function abort($code, $message = '', array $headers = [])
    {
        //
    }
}

if (!function_exists('app'))
{
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed
     */
    function app(string $abstract = null, array $parameters = []): mixed
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('base_path'))
{
    /**
     * Get the path to the base of the install.
     *
     * @param string $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return app()->basePath($path);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param string $path
     * @return string
     */
    function storage_path(string $path = '')
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('config'))
{
    /**
     * Get / set the specified configuration value.
     *
     * @param array|string|null $key
     * @param mixed $default
     * @return Repository
     */
    function config(array|string $key = null, mixed $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (!function_exists('cookie'))
{
    //
}

if (!function_exists('csrf_field'))
{
    /**
     * Generate a CSRF token form field.
     *
     * @return string
     */
    function csrf_field(): string
    {
        return new \Illuminate\Support\HtmlString('<input type="hidden" name="_token" value="'.csrf_token().'">');
    }
}

if (!function_exists('csrf_token'))
{
    /**
     * Get the CSRF token value.
     *
     * @return string
     * @throws RuntimeException
     */
    function csrf_token(): string
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (!function_exists('request'))
{
    /**
     * Get an instance of the current request.
     *
     * @return Request
     */
    function request()
    {
        return app('request');
    }
}

if (!function_exists('response'))
{
    /**
     * Return a new response from the application.
     *
     * @return Request
     */
    function response()
    {
        return app('response');
    }
}

if (!function_exists('session'))
{
    /**
     * Get / set the specified session value.
     *
     * @param  array|string|null $key
     * @param  mixed $default
     * @return mixed
     */
    function session(array|string|null $key = null, mixed $default = null):mixed
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (!function_exists('trans'))
{
    //
}

if (!function_exists('trans_choice'))
{
    //
}

if (!function_exists('__'))
{
    //
}

if (!function_exists('url'))
{
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}

if (!function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return Factory
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = []): Factory
    {
        $factory = app('validator');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
