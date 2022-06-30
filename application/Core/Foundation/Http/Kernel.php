<?php

namespace JSmart\Core\Foundation\Http;

use Closure;
use JSmart\Core\Foundation\Application;
use JSmart\Core\Foundation\Pipeline;
use JSmart\Core\Foundation\Http\Request;
use JSmart\Core\Foundation\Http\Response;
use JSmart\Core\Routing\Router;

class Kernel
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected array $middleware = [
        \JSmart\Core\Foundation\Http\Middleware\TrimStrings::class,
        \JSmart\Core\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ];

    /**
     * Create a new HTTP kernel instance.
     *
     * @param Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming HTTP request.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $response = $this->sendRequestThroughRouter($request);

        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        $this->app['event.dispatcher']->dispatch([
            'request' => $request, 'response' => $response
        ], 'kernel.handle');

        return $response;
    }

    /**
     * Send request through the middleware in router.
     *
     * @param Request $request
     * @return mixed
     */
    protected function sendRequestThroughRouter(Request $request): mixed
    {
        return (new Pipeline($this->app))
                    ->send($request)
                    ->through($this->middleware)
                    ->then($this->dispatchToRouter());
    }

    /**
     * Get the route dispatcher callback.
     *
     * @return Closure
     */
    protected function dispatchToRouter(): Closure
    {
        return function ($request): mixed {
            $this->app->instance('request', $request);

            return $this->app['router']->dispatch($request);
        };
    }
}
