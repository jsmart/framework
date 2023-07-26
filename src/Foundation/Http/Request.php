<?php

namespace JSmart\Foundation\Http;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\InputBag;

class Request extends SymfonyRequest
{
    use Macroable;

    /**
     * Generates a normalized URI (URL) for the Request.
     *
     * @return string
     */
    public function url(): string
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl(): string
    {
        $query = $this->getQueryString();

        $question = $this->getBaseUrl() . $this->getPathInfo() === '/' ? '/?' : '?';

        return $query ? $this->url() . $question.$query : $this->url();
    }

    /**
     * Get the full URL for the request with the added query string parameters.
     *
     * @param array $query
     * @return string
     */
    public function fullUrlWithQuery(array $query): string
    {
        $question = $this->getBaseUrl() . $this->getPathInfo() === '/' ? '/?' : '?';

        return count($this->query()) > 0
            ? $this->url() . $question . Arr::query(array_merge($this->query(), $query))
            : $this->fullUrl() . $question . Arr::query($query);
    }

    /**
     * Get the full URL for the request without the given query string parameters.
     *
     * @param array|string $keys
     * @return string
     */
    public function fullUrlWithoutQuery(array|string $keys): string
    {
        $query = Arr::except($this->query(), $keys);

        $question = $this->getBaseUrl().$this->getPathInfo() === '/' ? '/?' : '?';

        return count($query) > 0
            ? $this->url() . $question . Arr::query($query)
            : $this->url();
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path(): string
    {
        $pattern = trim($this->getPathInfo(), '/');

        return $pattern === '' ? '/' : $pattern;
    }

    /**
     * Get the current decoded path info for the request.
     *
     * @return string
     */
    public function decodedPath(): string
    {
        return rawurldecode($this->path());
    }

    /**
     * Get a segment from the URI (1 based index).
     *
     * @param int $index
     * @param string|null $default
     * @return ?string
     */
    public function segment(int $index, string $default = null): ?string
    {
        return Arr::get($this->segments(), $index - 1, $default);
    }

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public function segments(): array
    {
        $segments = explode('/', $this->decodedPath());

        return array_values(array_filter($segments, function ($value) {
            return $value !== '';
        }));
    }

    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param mixed ...$patterns
     * @return bool
     */
    public function is(mixed ...$patterns): bool
    {
        $path = $this->decodedPath();

        return collect($patterns)->contains(fn ($pattern) => Str::is($pattern, $path));
    }

    /**
     * Determine if the current request URL and query string match a pattern.
     *
     * @param mixed ...$patterns
     * @return bool
     */
    public function fullUrlIs(...$patterns): bool
    {
        $url = $this->fullUrl();

        return collect($patterns)->contains(fn ($pattern) => Str::is($pattern, $url));
    }

    /**
     * Determine if the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax(): bool
    {
        return $this->isXmlHttpRequest();
    }

    /**
     * Determine if the request is the result of a PJAX call.
     *
     * @return bool
     */
    public function pjax(): bool
    {
        return $this->headers->get('X-PJAX') == true;
    }

    /**
     * Gets the request "intended" method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->getMethod();
    }

    /**
     * Determine if the request is over HTTPS.
     *
     * @return bool
     */
    public function secure(): bool
    {
        return $this->isSecure();
    }

    /**
     * Get the client IP address.
     *
     * @return string|null
     */
    public function ip(): ?string
    {
        return $this->getClientIp();
    }

    /**
     * Get the client IP addresses.
     *
     * @return array
     */
    public function ips(): array
    {
        return $this->getClientIps();
    }

    /**
     * Get the client user agent.
     *
     * @return string|null
     */
    public function userAgent(): ?string
    {
        return $this->headers->get('User-Agent');
    }





    /**
     * Retrieve a request payload item from the request.
     *
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    public function post(string|null $key = null, string|array|null $default = null): string|array|null
    {
        return $this->retrieveItem('request', $key, $default);
    }

    /**
     * Retrieve a query string item from the request.
     *
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    public function query(string|null $key = null, string|array|null $default = null): string|array|null
    {
        return $this->retrieveItem('query', $key, $default);
    }

    /**
     * Retrieve a cookie from the request.
     *
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    public function cookies(string|null $key = null, string|array|null $default = null): string|array|null
    {
        return $this->retrieveItem('cookies', $key, $default);
    }

    public function attributes($key = null, $default = null)
    {
        return $this->retrieveItem('attributes', $key, $default);
    }

    public function files($key = null, $default = null): array|string|null
    {
        return $this->retrieveItem('files', $key, $default);
    }

    /**
     * Retrieve a server variable from the request.
     *
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    public function server(string|null $key = null, string|array|null $default = null): string|array|null
    {
        return $this->retrieveItem('server', $key, $default);
    }

    /**
     * Retrieve a header from the request.
     *
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    public function headers($key = null, $default = null): ?string
    {
        return $this->retrieveItem('headers', $key, $default);
    }

    /**
     * Retrieve a parameter item from a given source.
     *
     * @param string $source
     * @param string|null $key
     * @param string|array|null $default
     * @return string|array|null
     */
    protected function retrieveItem(string $source, string|null $key, string|array|null $default): string|array|null
    {
        if (empty($key)) {
            return $this->$source->all();
        }

        if ($this->$source instanceof InputBag) {
            return $this->$source->all()[$key] ?? $default;
        }

        return $this->$source->get($key, $default);
    }




    public function input($key = null, $default = null)
    {
        return data_get(
            $this->getInputSource()->all() + $this->query->all(), $key, $default
        );
    }

    protected function getInputSource()
    {
        //if ($this->isJson()) {
        //    return $this->json();
        //}

        return in_array($this->getRealMethod(), ['GET', 'HEAD']) ? $this->query : $this->request;
    }
}

