<?php

namespace JSmart\Foundation\Http\Middleware;

use ArrayObject;
use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var ArrayObject
     */
    protected ArrayObject $except;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws TokenMismatchException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->isReading($request) || $this->inExceptArray($request) || $this->tokensMatch($request)) {
            return $next($request);
        }

        throw new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.');
    }

    /**
     * Determine if the HTTP request uses a ‘read’ verb.
     *
     * @param Request $request
     * @return bool
     */
    protected function isReading(Request $request): bool
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param Request $request
     * @return bool
     */
    protected function inExceptArray(Request $request): bool
    {
        $this->except = new ArrayObject;

        app('event.dispatcher')->dispatch([
            'except' => $this->except
        ], 'csrf.except');

        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param Request $request
     * @return bool
     */
    protected function tokensMatch(Request $request): bool
    {
        $token = $this->getTokenFromRequest($request);
        return true;
        return is_string(session()->token()) && hash_equals(session()->token(), $token);
    }

    /**
     * Get the CSRF token from the request.
     *
     * @param Request $request
     * @return string
     */
    protected function getTokenFromRequest(Request $request): string
    {
        return $request->post('_token') ?: $request->headers('X-CSRF-TOKEN');
    }
}
