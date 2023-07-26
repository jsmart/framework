<?php

namespace JSmart\Foundation;

use Closure;

class Pipeline
{
    /**
     * The application implementation.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The object being passed through the pipeline.
     *
     * @var mixed
     */
    protected mixed $passable;

    /**
     * The array of class pipes.
     *
     * @var array
     */
    protected array $pipes = [];

    /**
     * Create a new class instance.
     *
     * @param Application|null $app
     */
    public function __construct(Application $app = null)
    {
        $this->app = $app;
    }

    /**
     * Set the object being sent through the pipeline.
     *
     * @param mixed $passable
     * @return $this
     */
    public function send(mixed $passable): static
    {
        $this->passable = $passable;

        return $this;
    }

    /**
     * Set the array of pipes.
     *
     * @param mixed $pipes
     * @return $this
     */
    public function through(mixed $pipes): static
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();

        return $this;
    }

    /**
     * Run the pipeline with a final destination callback.
     *
     * @param mixed $destination
     * @return mixed
     */
    public function then(mixed $destination): mixed
    {
        $callable = $destination;

        foreach (array_reverse($this->pipes) as $pipe) {
            $callable = function ($passable) use ($pipe, $callable): mixed {
                $pipe = $this->app->make($pipe);
                return $pipe->handle($passable, $callable);
            };
        }

        return $callable($this->passable);
    }
}
