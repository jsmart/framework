<?php

namespace JSmart\Foundation\Http;

use Closure;
use JSmart\Foundation\Application;
use JSmart\Foundation\Pipeline;
use JSmart\Http\Request;
use JSmart\Http\Response;
//use Illuminate\Http\Response;

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
        \JSmart\Foundation\Http\Middleware\TrimStrings::class,
        \JSmart\Session\Middleware\StartSession::class,
        \JSmart\Foundation\Http\Middleware\VerifyCsrfToken::class,
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
        $request->enableHttpMethodParameterOverride();

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
        $this->app->instance('request', $request);

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
