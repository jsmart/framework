<?php

namespace JSmart\Session\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Symfony\Component\HttpFoundation\Cookie;

class StartSession
{
    /**
     * The session manager.
     */
    protected SessionManager $manager;

    /**
     * Create a new session middleware.
     */
    public function __construct(SessionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$this->sessionConfigured()) {
            return $next($request);
        }

        return $this->handleStatefulRequest($request, $this->getSession($request), $next);
    }

    /**
     * Handle the given request within session state.
     */
    protected function handleStatefulRequest(Request $request, Store $session, Closure $next): mixed
    {
        $request->setLaravelSession(
            $this->startSession($request, $session)
        );

        $this->collectGarbage($session);

        $response = $next($request);

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $this->storeCurrentUrl($request, $session);

        $this->addCookieToResponse($response, $session);

        $this->saveSession($request);

        return $response;
    }

    /**
     * Start the session for the given request.
     */
    protected function startSession(Request $request, Store $session): Store
    {
        return tap($session, function ($session) use ($request) {
            $session->setRequestOnHandler($request);

            $session->start();
        });
    }

    /**
     * Get the session implementation from the manager.
     */
    public function getSession(Request $request): Store
    {
        return tap($this->manager->driver(), function ($session) use ($request) {
            $session->setId($request->cookies->get($session->getName()));
        });
    }

    /**
     * Remove the garbage from the session if necessary.
     */
    protected function collectGarbage(Store $session): void
    {
        $config = $this->manager->getSessionConfig();

        if ($this->configHitsLottery($config)) {
            $session->getHandler()->gc($this->getSessionLifetimeInSeconds());
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     */
    protected function configHitsLottery(array $config): bool
    {
        return random_int(1, $config['lottery'][1]) <= $config['lottery'][0];
    }

    /**
     * Store the current URL for the request if necessary.
     */
    protected function storeCurrentUrl(Request $request, Store $session): void
    {
        if ($request->isMethod('GET') && !$request->ajax() && !$request->prefetch() && !$request->isPrecognitive()) {
            $session->setPreviousUrl($request->fullUrl());
        }
    }

    /**
     * Add the session cookie to the application response.
     */
    protected function addCookieToResponse(Response $response, Store $session): void
    {
        if ($this->sessionIsPersistent($config = $this->manager->getSessionConfig())) {
            $response->headers->setCookie(new Cookie(
                $session->getName(), $session->getId(), $this->getCookieExpirationDate(),
                $config['path'], $config['domain'], $config['secure'] ?? false,
                $config['http_only'] ?? true, false, $config['same_site'] ?? null
            ));
        }
    }

    /**
     * Save the session data to storage.
     */
    protected function saveSession(Request $request): void
    {
        $this->manager->driver()->save();
    }

    /**
     * Get the session lifetime in seconds.
     */
    protected function getSessionLifetimeInSeconds(): int
    {
        return ($this->manager->getSessionConfig()['lifetime'] ?? null) * 60;
    }

    /**
     * Get the cookie lifetime in seconds.
     */
    protected function getCookieExpirationDate(): int
    {
        $config = $this->manager->getSessionConfig();

        return $config['expire_on_close'] ? 0 : time() + $config['lifetime'] * 60;
    }

    /**
     * Determine if a session driver has been configured.
     */
    protected function sessionConfigured(): bool
    {
        return ! is_null($this->manager->getSessionConfig()['driver'] ?? null);
    }

    /**
     * Determine if the configured session driver is persistent.
     */
    protected function sessionIsPersistent(array $config = null): bool
    {
        $config = $config ?: $this->manager->getSessionConfig();

        return ! is_null($config['driver'] ?? null);
    }
}