<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use Closure;

/**
 * Router class for handling routes
 * Implements SRP and OCP principles
 */
class Router
{
    /**
     * @var array<string, array>
     */
    private array $routes = [];

    /**
     * @var array<string, array>
     */
    private array $routeGroups = [];

    /**
     * @var array<string>
     */
    private array $middlewareStack = [];

    /**
     * Current route group prefix
     *
     * @var string
     */
    private string $groupPrefix = '';

    /**
     * Current route group middleware
     *
     * @var array<string>
     */
    private array $groupMiddleware = [];

    /**
     * Register a GET route
     *
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    public function get(string $uri, string|Closure|array $action): Route
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a POST route
     *
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    public function post(string $uri, string|Closure|array $action): Route
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a PUT route
     *
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    public function put(string $uri, string|Closure|array $action): Route
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a DELETE route
     *
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    public function delete(string $uri, string|Closure|array $action): Route
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a route for any HTTP method
     *
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    public function any(string $uri, string|Closure|array $action): Route
    {
        return $this->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'], $uri, $action);
    }

    /**
     * Create a route group
     *
     * @param array $attributes
     * @param Closure $callback
     * @return void
     */
    public function group(array $attributes, Closure $callback): void
    {
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;

        if (isset($attributes['prefix'])) {
            $prefix = trim($attributes['prefix'], '/');
            $this->groupPrefix = $previousPrefix ? $previousPrefix . '/' . $prefix : '/' . $prefix;
        }

        if (isset($attributes['middleware'])) {
            $this->groupMiddleware = array_merge($previousMiddleware, (array) $attributes['middleware']);
        }

        $callback($this);

        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
    }

    /**
     * Add a route to the collection
     *
     * @param string|array $methods
     * @param string $uri
     * @param string|Closure|array $action
     * @return Route
     */
    private function addRoute(string|array $methods, string $uri, string|Closure|array $action): Route
    {
        $methods = (array) $methods;
        
        // Build full URI with group prefix
        $uri = trim($uri, '/');
        if ($this->groupPrefix) {
            $uri = trim($this->groupPrefix, '/') . '/' . $uri;
        }
        $uri = '/' . trim($uri, '/');
        if ($uri === '//') {
            $uri = '/';
        }

        $route = new Route($methods, $uri, $action);
        
        // Apply group middleware
        if (!empty($this->groupMiddleware)) {
            $route->middleware($this->groupMiddleware);
        }

        foreach ($methods as $method) {
            $this->routes[$method][$uri] = $route;
        }

        return $route;
    }

    /**
     * Dispatch the request to the appropriate route
     *
     * @param string $method
     * @param string $uri
     * @return mixed
     */
    public function dispatch(string $method, string $uri): mixed
    {
        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$method])) {
            return null;
        }

        // Try exact match first
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        // Try pattern matching
        foreach ($this->routes[$method] as $pattern => $route) {
            if ($this->matchRoute($pattern, $uri)) {
                // Extract parameters during matching
                $route->extractParameters($uri);
                return $route;
            }
        }

        return null;
    }

    /**
     * Normalize URI
     *
     * @param string $uri
     * @return string
     */
    private function normalizeUri(string $uri): string
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');
        return $uri === '/' ? '/' : $uri;
    }

    /**
     * Match route pattern with URI
     *
     * @param string $pattern
     * @param string $uri
     * @return bool
     */
    private function matchRoute(string $pattern, string $uri): bool
    {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return (bool) preg_match($pattern, $uri);
    }

    /**
     * Get all routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

