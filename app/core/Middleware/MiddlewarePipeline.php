<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Middleware;

use Closure;

/**
 * Middleware Pipeline
 * Implements SRP principle
 */
class MiddlewarePipeline
{
    /**
     * @var array<MiddlewareInterface|string>
     */
    private array $middleware = [];

    /**
     * @var \KenDeNigerian\Krak\core\Container
     */
    private \KenDeNigerian\Krak\core\Container $container;

    /**
     * @param \KenDeNigerian\Krak\core\Container $container
     */
    public function __construct(\KenDeNigerian\Krak\core\Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add middleware to the pipeline
     *
     * @param MiddlewareInterface|string|array $middleware
     * @return self
     */
    public function add(MiddlewareInterface|string|array $middleware): self
    {
        if (is_array($middleware)) {
            $this->middleware = array_merge($this->middleware, $middleware);
        } else {
            $this->middleware[] = $middleware;
        }

        return $this;
    }

    /**
     * Process the request through the middleware pipeline
     *
     * @param mixed $request
     * @param Closure $handler
     * @return mixed
     */
    public function process(mixed $request, Closure $handler): mixed
    {
        // If no middleware, just execute the handler
        if (empty($this->middleware)) {
            return $handler($request);
        }

        $pipeline = array_reduce(
            array_reverse($this->middleware),
            function (Closure $carry, $middleware) {
                return function ($request) use ($carry, $middleware) {
                    $instance = $this->resolveMiddleware($middleware);
                    return $instance->handle($request, $carry);
                };
            },
            $handler
        );

        return $pipeline($request);
    }

    /**
     * Resolve middleware instance
     *
     * @param MiddlewareInterface|string $middleware
     * @return MiddlewareInterface
     */
    private function resolveMiddleware(MiddlewareInterface|string $middleware): MiddlewareInterface
    {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        if ($this->container->bound($middleware)) {
            return $this->container->get($middleware);
        }

        // Try to instantiate from namespace (lowercase to match directory structure)
        $class = "KenDeNigerian\Krak\\middleware\\{$middleware}";
        if (class_exists($class)) {
            return $this->container->make($class);
        }

        throw new \Exception("Middleware {$middleware} could not be resolved");
    }
}

