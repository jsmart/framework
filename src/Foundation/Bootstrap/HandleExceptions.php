<?php

namespace JSmart\Foundation\Bootstrap;

use ErrorException;
use Exception;

use Illuminate\Contracts\Container\BindingResolutionException;
use JSmart\Facades\View;
use JSmart\Foundation\Application;

use JSmart\Foundation\Exceptions\Handler;
use JSmart\Http\Response;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\ErrorHandler\ErrorHandler;

class HandleExceptions
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Reserved memory so that errors can be displayed properly on memory exhaustion.
     *
     * @var string|null
     */
    public static ?string $reservedMemory;

    /**
     * Bootstrap the given application.
     *
     * @param Application $app
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        self::$reservedMemory = str_repeat('x', 32768);

        static::$app = $app;

        error_reporting(-1);

        set_error_handler($this->forwardsTo('handleError'));

        set_exception_handler($this->forwardsTo('handleException'));

        register_shutdown_function($this->forwardsTo('handleShutdown'));

        if (!$app->config['application.debug']) {
            ini_set('display_errors', 'Off');
        }

    //    /*
        if ($app->config['application.debug']) {
            //Debug::enable()->setDefaultLogger($app->get('log'));
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
        }
        else {
            //ErrorHandler::register()->setDefaultLogger($app->get('log'));
        }

        //set_exception_handler($this->forwardsTo('handleException'));
     //   */
    }

    /**
     * Report PHP deprecations, or convert PHP errors to ErrorException instances.
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @return void
     * @throws ErrorException
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0): void
    {
        // Deprecation error in log file ... ?

        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * @param $error
     * @return void
     */
    public function handleException($error): void
    {
        self::$reservedMemory = null;

        try {
            $this->getExceptionHandler()->report($error);
        } catch (Exception) {
            //
        }

        dd('.....');
        (new Response(View::file('application/core/Foundation/Exceptions/Views/minimal.php', [])))->send();
        //dump($e);
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown(): void
    {
        self::$reservedMemory = null;

        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalErrorFromPhpError($error, 0));
        }
    }

    /**
     * Create a new fatal error instance from an error array.
     *
     * @param array $error
     * @param int|null $traceOffset
     * @return FatalError
     */
    protected function fatalErrorFromPhpError(array $error, int $traceOffset = null): FatalError
    {
        return new FatalError($error['message'], 0, $error, $traceOffset);
    }

    /**
     * Forward a method call to the given method if an application instance exists.
     *
     * @param $method
     * @return callable
     */
    protected function forwardsTo($method): callable
    {
        return fn (...$arguments) => static::$app
            ? $this->{$method}(...$arguments)
            : false;
    }

    /**
     * Determine if the error level is a deprecation.
     *
     * @param int $level
     * @return bool
     */
    protected function isDeprecation(int $level): bool
    {
        return in_array($level, [E_DEPRECATED, E_USER_DEPRECATED]);
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param int $type
     * @return bool
     */
    protected function isFatal(int $type): bool
    {
        return in_array($type, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE]);
    }

    /**
     * Get an instance of the exception handler.
     *
     */
    protected function getExceptionHandler()
    {
        return static::$app->make(Handler::class);
    }
}
