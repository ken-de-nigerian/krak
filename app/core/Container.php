<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * Service Container for Dependency Injection
 * Implements SRP and OCP principles
 */
class Container
{
    /**
     * @var array<string, mixed>
     */
    private array $bindings = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * Bind a class or closure to the container
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @param bool $singleton
     * @return void
     */
    public function bind(string $abstract, string|Closure|null $concrete = null, bool $singleton = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'singleton' => $singleton
        ];
    }

    /**
     * Bind a singleton instance
     *
     * @param string $abstract
     * @param string|Closure|null $concrete
     * @return void
     */
    public function singleton(string $abstract, string|Closure|null $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Resolve a class from the container
     *
     * @param string $abstract
     * @return mixed
     * @throws ReflectionException
     */
    public function make(string $abstract): mixed
    {
        // Return instance if already resolved as singleton
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Check if bound
        if (isset($this->bindings[$abstract])) {
            $binding = $this->bindings[$abstract];
            $concrete = $binding['concrete'];

            if ($concrete instanceof Closure) {
                $instance = $concrete($this);
            } else {
                $instance = $this->build($concrete);
            }

            if ($binding['singleton']) {
                $this->instances[$abstract] = $instance;
            }

            return $instance;
        }

        // Try to auto-resolve
        return $this->build($abstract);
    }

    /**
     * Build a class instance with dependency injection
     *
     * @param string $concrete
     * @return object
     * @throws ReflectionException
     */
    private function build(string $concrete): object
    {
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $concrete();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve constructor dependencies
     *
     * @param array<ReflectionParameter> $parameters
     * @return array
     * @throws ReflectionException
     */
    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type === null || !$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve dependency {$parameter->getName()}");
                }
            } else {
                $dependencies[] = $this->make($type->getName());
            }
        }

        return $dependencies;
    }

    /**
     * Check if a binding exists
     *
     * @param string $abstract
     * @return bool
     */
    public function bound(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }

    /**
     * Get an instance (alias for make)
     *
     * @param string $abstract
     * @return mixed
     * @throws ReflectionException
     */
    public function get(string $abstract): mixed
    {
        return $this->make($abstract);
    }
}

