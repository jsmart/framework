<?php

namespace JSmart\Session\Middleware;

use Closure;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StartSession
{
    /**
     * The session manager.
     *
     * @var SessionManager
     */
    protected SessionManager $manager;

    /**
     * Create a new session middleware.
     *
     * @param SessionManager $manager
     * @return void
     */
    public function __construct(SessionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
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
     *
     * @param Request $request
     * @param Store $session
     * @param Closure $next
     * @return mixed
     */
    protected function handleStatefulRequest(Request $request, Store $session, Closure $next): mixed
    {
        $this->startSession($request, $session);

        $this->collectGarbage($session);

        $response = $next($request);

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $this->addCookieToResponse($response, $session);

        $this->saveSession($request);

        return $response;
    }

    /**
     * Start the session for the given request.
     *
     * @param Request $request
     * @param Store $session
     * @return Store
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
     *
     * @param Request $request
     * @return Store
     */
    public function getSession(Request $request): Store
    {
        return tap($this->manager->driver(), function ($session) use ($request) {
            $session->setId($request->cookies->get($session->getName()));
        });
    }

    /**
     * Remove the garbage from the session if necessary.
     *
     * @param Store $session
     * @return void
     */
    protected function collectGarbage(Store $session)
    {
        $config = $this->manager->getSessionConfig();

        if ($this->configHitsLottery($config)) {
            $session->getHandler()->gc($this->getSessionLifetimeInSeconds());
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param array $config
     * @return bool
     */
    protected function configHitsLottery(array $config): bool
    {
        return random_int(1, $config['lottery'][1]) <= $config['lottery'][0];
    }

    /**
     * Add the session cookie to the application response.
     *
     * @param Response $response
     * @param Store $session
     * @return void
     */
    protected function addCookieToResponse(Response $response, Store $session)
    {
        if ($this->sessionIsPersistent($config = $this->manager->getSessionConfig())) {
            $response->headers->setCookie(new \Symfony\Component\HttpFoundation\Cookie(
                $session->getName(), $session->getId(), $this->getCookieExpirationDate(),
                $config['path'], $config['domain'], $config['secure'] ?? false,
                $config['http_only'] ?? true, false, $config['same_site'] ?? null
            ));
        }
    }

    /**
     * Save the session data to storage.
     *
     * @param Request $request
     * @return void
     */
    protected function saveSession(Request $request): void
    {
        $this->manager->driver()->save();
    }

    /**
     * Get the session lifetime in seconds.
     *
     * @return int
     */
    protected function getSessionLifetimeInSeconds(): int
    {
        return ($this->manager->getSessionConfig()['lifetime'] ?? null) * 60;
    }

    /**
     * Get the cookie lifetime in seconds.
     *
     * @return int
     */
    protected function getCookieExpirationDate(): int
    {
        $config = $this->manager->getSessionConfig();

        return $config['expire_on_close'] ? 0 : time() + $config['lifetime'] * 60;
    }

    /**
     * Determine if a session driver has been configured.
     *
     * @return bool
     */
    protected function sessionConfigured(): bool
    {
        return ! is_null($this->manager->getSessionConfig()['driver'] ?? null);
    }

    /**
     * Determine if the configured session driver is persistent.
     *
     * @param array|null $config
     * @return bool
     */
    protected function sessionIsPersistent(array $config = null): bool
    {
        $config = $config ?: $this->manager->getSessionConfig();

        return ! is_null($config['driver'] ?? null);
    }
}