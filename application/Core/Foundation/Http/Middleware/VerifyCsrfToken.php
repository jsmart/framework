<?php

namespace JSmart\Core\Foundation\Http\Middleware;

use Closure;
use JSmart\Core\Foundation\Http\Request;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
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
        return $next($request);
    }

    /**
     * Get the CSRF token from the request.
     *
     * @param Request $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        $token = $request->post('_token') ?: $request->headers('X-CSRF-TOKEN');

        return $token;
    }
}
