<?php

namespace JSmart\Config;

use ArrayAccess;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;

class Repository implements ArrayAccess, ConfigContract
{
    protected array $items = [];

    /**
     * Create a new configuration repository.
     *
     * @param  array  $items
     * @return void
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key): bool
    {
        return Arr::has($this->items, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            Arr::set($this->items, $key, $value);
        }
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key, []);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function push($key, $value)
    {
        $array = $this->get($key, []);

        $array[] = $value;

        $this->set($key, $array);
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param string $key
     * @return bool
     */
    public function offsetExists(mixed $key): bool
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param string $key
     * @return void
     */
    public function offsetUnset(mixed $key): void
    {
        $this->set($key, null);
    }
}