<?php

namespace JSmart\Foundation;

use JSmart\Events\EventServiceProvider;
use JSmart\Log\LogServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Application extends \Illuminate\Container\Container
{
    /**
     * The JSmart framework version.
     */
    const VERSION = '3.0.0';

    /**
     * The base path for the JSmart installation.
     */
    protected string $basePath;

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected array $bootstrappers = [
        \JSmart\Foundation\Bootstrap\LoadConfiguration::class,
        \JSmart\Foundation\Bootstrap\HandleExceptions::class,
        \JSmart\Foundation\Bootstrap\RegisterProviders::class,
        \JSmart\Foundation\Bootstrap\RegisterAliases::class,
        \JSmart\Foundation\Bootstrap\RegisterFacades::class,
        \JSmart\Foundation\Bootstrap\RegisterListeners::class,
        \JSmart\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * All the registered service providers.
     */
    protected array $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     */
    protected array $loadedProviders = [];

    /**
     * Create a new JSmart application instance.
     */
    public function __construct(?string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $this->bootstrap();
    }

    /**
     * Set the base path for the application.
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '\/');
    }

    /**
     * Get the base path of the JSmart installation.
     */
    public function basePath(string $path = ''): string
    {
        return $this->joinPaths($this->basePath, $path);
    }

    /**
     * Get the path to the application "application" directory.
     */
    public function applicationPath(string $path = ''): string
    {
        return $this->joinPaths($this->basePath('application'), $path);
    }

    /**
     * Get the path to the application configuration files.
     */
    public function configPath(): string
    {
        return $this->joinPaths($this->applicationPath(), 'config');
    }

    /**
     * Get the path to the database directory.
     */
    public function databasePath(string $path = ''): string
    {
        return $this->joinPaths($this->applicationPath('database'), $path);
    }

    /**
     * Get the path to the modules dir.
     */
    public function modulesPath(string $path = ''): string
    {
        return $this->joinPaths($this->applicationPath('modules'), $path);
    }

    /**
     * Get the path to the storage dir.
     */
    public function storagePath(string $path = ''): string
    {
        return $this->joinPaths($this->applicationPath('storage'), $path);
    }

    /**
     * Join the given paths together.
     */
    public function joinPaths(string $basePath, string $path = ''): string
    {
        return $basePath . ($path != '' ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    /**
     * Determine if the application is running in the console.
     */
    public function runningInConsole(): bool
    {
        return (\PHP_SAPI === 'cli' || \PHP_SAPI === 'phpdbg');
    }

    /**
     * Register the basic bindings into the container.
     */
    protected function registerBaseBindings(): void
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance(\Illuminate\Container\Container::class, $this);

        $this->register(new EventServiceProvider($this));
        $this->register(new LogServiceProvider($this));
    }

    /**
     * Run bootstrap classes.
     */
    protected function bootstrap(): void
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Register a service provider with the application.
     */
    public function register(ServiceProvider|string $provider): ServiceProvider
    {
        if ($registered = $this->getProvider($provider)) {
            return $registered;
        }

        if (is_string($provider)) {
            $provider = $this->resolveProvider($provider);
        }

        $provider->register();

        if (property_exists($provider, 'bindings')) {
            foreach ($provider->bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }

        if (property_exists($provider, 'singletons')) {
            foreach ($provider->singletons as $key => $value) {
                $this->singleton($key, $value);
            }
        }

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     */
    public function getProvider(ServiceProvider|string $provider): ?ServiceProvider
    {
        $provider = is_string($provider) ? $provider : get_class($provider);

        $serviceProviders = array_filter($this->serviceProviders, function ($value) use ($provider) {
            return $value instanceof $provider;
        }, ARRAY_FILTER_USE_BOTH);

        return array_values($serviceProviders)[0] ?? null;
    }

    /**
     * Resolve a service provider instance from the class name.
     */
    public function resolveProvider(string $provider): ServiceProvider
    {
        return new $provider($this);
    }

    /**
     * Mark the given provider as registered.
     */
    protected function markAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Boot the application's service providers.
     */
    public function bootProviders(): void
    {
        array_walk($this->serviceProviders, function ($provider) {
            $this->bootProvider($provider);
        });
    }

    /**
     * Boot the given service provider.
     */
    public function bootProvider(ServiceProvider $provider): void
    {
        if (method_exists($provider, 'boot')) {
            $this->call([$provider, 'boot']);
        }
    }

    /**
     * Throw an HttpException with the given data.
     */
    public function abort(int $code, string $message = '', array $headers = []): never
    {
        if ($code == 404) {
            throw new NotFoundHttpException($message, null, 0, $headers);
        }

        throw new HttpException($code, $message, null, $headers);
    }
}
