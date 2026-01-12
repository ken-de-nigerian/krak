<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use Closure;

/**
 * Route class representing a single route
 * Implements SRP principle
 */
class Route
{
    /**
     * @var array<string>
     */
    private array $methods;

    /**
     * @var string
     */
    private string $uri;

    /**
     * @var string|Closure|array
     */
    private string|Closure|array $action;

    /**
     * @var array<string>
     */
    private array $middleware = [];

    /**
     * @var array<string, string>
     */
    private array $parameters = [];

    /**
     * @param array<string> $methods
     * @param string $uri
     * @param string|Closure|array $action
     */
    public function __construct(array $methods, string $uri, string|Closure|array $action)
    {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->action = $action;
    }

    /**
     * Add middleware to the route
     *
     * @param string|array<string> $middleware
     * @return self
     */
    public function middleware(string|array $middleware): self
    {
        $this->middleware = array_merge($this->middleware, (array) $middleware);
        return $this;
    }

    /**
     * Get route methods
     *
     * @return array<string>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Get route URI
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Get route action
     *
     * @return string|Closure|array
     */
    public function getAction(): string|Closure|array
    {
        return $this->action;
    }

    /**
     * Get route middleware
     *
     * @return array<string>
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    /**
     * Set route parameters
     *
     * @param array<string, string> $parameters
     * @return self
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Get route parameters
     *
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Extract parameters from URI
     *
     * @param string $uri
     * @return void
     */
    public function extractParameters(string $uri): void
    {
        $pattern = $this->uri;
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $this->parameters[$key] = $value;
                }
            }
        }
    }
}

