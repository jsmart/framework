<?php

namespace JSmart\Core\Foundation;

use JSmart\Core\Events\EventServiceProvider;
use JSmart\Core\Log\LogServiceProvider;
use JSmart\Core\Foundation\ServiceProvider;

class Application extends \Illuminate\Container\Container
{
    /**
     * The JSmart framework version.
     *
     * @var string
     */
    const VERSION = '3.0.0';

    /**
     * The base path for the JSmart installation.
     *
     * @var string
     */
    protected string $basePath;

    /**
     * The bootstrap classes for the application.
     *
     * @var string[]
     */
    protected array $bootstrappers = [
        \JSmart\Core\Foundation\Bootstrap\LoadConfiguration::class,
        \JSmart\Core\Foundation\Bootstrap\HandleExceptions::class,
        \JSmart\Core\Foundation\Bootstrap\RegisterProviders::class,
        \JSmart\Core\Foundation\Bootstrap\RegisterAliases::class,
        \JSmart\Core\Foundation\Bootstrap\RegisterFacades::class,
        \JSmart\Core\Foundation\Bootstrap\RegisterListeners::class,
        \JSmart\Core\Foundation\Bootstrap\BootProviders::class,
    ];

    /**
     * All of the registered service providers.
     *
     * @var ServiceProvider[]
     */
    protected array $serviceProviders = [];

    /**
     * The names of the loaded service providers.
     *
     * @var array
     */
    protected array $loadedProviders = [];

    /**
     * Create a new JSmart application instance.
     *
     * @param string|null $basePath
     */
    public function __construct(string $basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $this->bootstrap();
    }

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = rtrim($basePath, '\/');
    }

    /**
     * Get the base path of the JSmart installation.
     *
     * @param string $path
     * @return string
     */
    public function basePath(string $path = ''): string
    {
        return $this->basePath . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath(): string
    {
        return $this->basePath('application/Config');
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
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
     *
     * @return void
     */
    protected function bootstrap(): void
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Register a service provider with the application.
     *
     * @param ServiceProvider|string $provider
     * @return ServiceProvider
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

        $this->markAsRegistered($provider);

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param ServiceProvider|string $provider
     * @return ServiceProvider|null
     */
    public function getProvider(ServiceProvider|string $provider): ServiceProvider|null
    {
        $provider = is_string($provider) ? $provider : get_class($provider);

        $serviceProviders = array_filter($this->serviceProviders, function ($value) use ($provider) {
            return $value instanceof $provider;
        }, ARRAY_FILTER_USE_BOTH);

        return array_values($serviceProviders)[0] ?? null;
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     * @return ServiceProvider
     */
    public function resolveProvider(string $provider): ServiceProvider
    {
        return new $provider($this);
    }

    /**
     * Mark the given provider as registered.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    protected function markAsRegistered(ServiceProvider $provider): void
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function bootProviders(): void
    {
        array_walk($this->serviceProviders, function ($provider) {
            $this->bootProvider($provider);
        });
    }

    /**
     * Boot the given service provider.
     *
     * @param ServiceProvider $provider
     * @return void
     */
    public function bootProvider(ServiceProvider $provider): void
    {
        if (method_exists($provider, 'boot')) {
            $this->call([$provider, 'boot']);
        }
    }
}
