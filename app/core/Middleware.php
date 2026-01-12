<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

/**
 * The App middleware which run services before the core software is being executed
 */
class Middleware
{
    /**
     * The list of routes to be excluded from being affected by middleware
     * Array Map: Key (Middleware) => Array (Routes)
     * @var array
     */
    protected array $except = [
        'CsrfToken' => []
    ];

    /**
     * Middleware to be loaded
     * @var array
     */
    private array $middleware = [];

    /**
     * Constructor method.
     * Loads and runs middleware.
     */
    public function __construct()
    {
        $this->getAll();

        // Check if URL parameter is set (assuming routing is based on URL parameters)
        if (isset($_GET['url'])) {
            foreach ($this->middleware as $name) {
                if (isset($this->except[$name])) {
                    foreach ($this->except[$name] as $route) {
                        // Check if the route matches any excluded routes
                        if (str_ends_with($route, '*')) {
                            if (stripos($_GET['url'], str_replace('*', '', $route)) === 0) {
                                return;
                            }
                        } elseif ($_GET['url'] == $route) {
                            return;
                        }
                    }
                }
                // Load and instantiate middleware classes
                require_once(__DIR__ . '/../middleware/' . $name . '.php');
                $class = 'KenDeNigerian\Krak\Middleware\\' . $name;
                new $class;
            }
        }
    }

    /**
     * Get all middleware files from the middleware directory.
     */
    private function getAll(): void
    {
        if ($handle = opendir(__DIR__ . '/../middleware/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && str_ends_with($entry, '.php')) {
                    $name = pathinfo($entry);
                    $this->middleware[] = $name['filename'];
                }
            }
            closedir($handle);
        }
    }
}
