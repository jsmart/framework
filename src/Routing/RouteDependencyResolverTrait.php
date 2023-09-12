<?php

namespace JSmart\Routing;

use Illuminate\Support\Arr;
use Illuminate\Support\Reflector;

use ReflectionMethod;
use ReflectionFunctionAbstract;
use ReflectionParameter;
use stdClass;

trait RouteDependencyResolverTrait
{
    /**
     * Resolve the object method's type-hinted dependencies.
     */
    protected function resolveClassMethodDependencies(array $parameters, object $instance, string $method): array
    {
        if (!method_exists($instance, $method)) {
            return $parameters;
        }

        return $this->resolveMethodDependencies(
            $parameters, new ReflectionMethod($instance, $method)
        );
    }

    /**
     * Resolve the given method's type-hinted dependencies.
     */
    protected function resolveMethodDependencies(array $parameters, ReflectionFunctionAbstract $reflector): array
    {
        $instanceCount = 0;

        $values = array_values($parameters);

        $skippableValue = new stdClass;

        foreach ($reflector->getParameters() as $key => $parameter) {
            $instance = $this->transformDependency($parameter, $parameters, $skippableValue);

            if ($instance !== $skippableValue) {
                $instanceCount++;

                $this->spliceIntoParameters($parameters, $key, $instance);
            }
            elseif (!isset($values[$key - $instanceCount]) && $parameter->isDefaultValueAvailable()) {
                $this->spliceIntoParameters($parameters, $key, $parameter->getDefaultValue());
            }
        }

        return $parameters;
    }

    /**
     * Attempt to transform the given parameter into a class instance.
     */
    protected function transformDependency(ReflectionParameter $parameter, array $parameters, object $skippableValue): mixed
    {
        $className = Reflector::getParameterClassName($parameter);

        if ($className && !$this->alreadyInParameters($className, $parameters)) {
            return $parameter->isDefaultValueAvailable() ? null : $this->app->make($className);
        }

        return $skippableValue;
    }

    /**
     * Determine if an object of the given class is in a list of parameters.
     */
    protected function alreadyInParameters(string $class, array $parameters): bool
    {
        return !is_null(Arr::first($parameters, function ($value) use ($class) {
            return $value instanceof $class;
        }));
    }

    /**
     * Splice the given value into the parameter list.
     */
    protected function spliceIntoParameters(array &$parameters, string $offset, mixed $value): void
    {
        array_splice(
            $parameters, $offset, 0, [$value]
        );
    }
}
