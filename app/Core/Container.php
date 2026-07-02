<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

final class Container
{
    private array $bindings = [];

    private array $instances = [];

    private array $aliases = [];

    public function alias(string $abstract, string $concrete): void
    {
        $this->aliases[$abstract] = $concrete;
    }

    public function singleton(string $abstract, Closure $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    public function get(string $abstract): object
    {
        $abstract = $this->aliases[$abstract] ?? $abstract;
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        if (isset($this->bindings[$abstract])) {
            $factory = $this->bindings[$abstract];
            $instance = $factory($this);
            $this->instances[$abstract] = $instance;

            return $instance;
        }

        return $this->resolve($abstract);
    }

    public function make(string $class): object
    {
        $class = $this->aliases[$class] ?? $class;

        return $this->resolve($class);
    }

    private function resolve(string $class): object
    {
        try {
            $reflection = new ReflectionClass($class);
        } catch (ReflectionException) {
            throw new RuntimeException("Class [{$class}] not found.");
        }

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException("Class [{$class}] is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                    continue;
                }

                throw new RuntimeException(
                    "Unable to resolve parameter [{$parameter->getName()}] for class [{$class}]."
                );
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
