<?php

namespace JSmart\Foundation\Http\Middleware;

use Closure;
use JSmart\Http\Request;

class TrimStrings
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array
     */
    protected array $except = [];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $request->request->replace($this->cleanArray($request->request->all()));

        return $next($request);
    }

    /**
     * Clean the data in the given array.
     *
     * @param array $data
     * @param string $keyPrefix
     * @return array
     */
    protected function cleanArray(array $data, string $keyPrefix = ''): array
    {
        foreach ($data as $key => $value) {
            $data[$key] = $this->cleanValue($keyPrefix . $key, $value);
        }

        return collect($data)->all();
    }

    /**
     * Clean the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function cleanValue(string $key, mixed $value): mixed
    {
        if (is_array($value)) {
            return $this->cleanArray($value, $key . '.');
        }

        return $this->transform($key, $value);
    }

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform(string $key, mixed $value): mixed
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        if (is_string($value)) {
            $value = preg_replace('~^\s+|\s+$~iu', '', $value);
        }

        return !empty($value) ? $value : null;
    }
}
