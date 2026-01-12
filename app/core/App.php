<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use KenDeNigerian\Krak\core\Router;
use KenDeNigerian\Krak\core\Container;
use KenDeNigerian\Krak\core\Cache\CacheInterface;
use KenDeNigerian\Krak\core\Cache\FileCache;
use KenDeNigerian\Krak\core\ServiceProvider;
use KenDeNigerian\Krak\core\Events\EventServiceProvider;
use KenDeNigerian\Krak\core\Database;
use Medoo\Medoo;
use KenDeNigerian\Krak\core\Middleware\MiddlewarePipeline;

/**
 * Refactored App class following SRP and OCP principles
 */
class App
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var Router
     */
    private Router $router;

    /**
     * @var Medoo
     */
    private Medoo $db;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * App constructor.
     */
    public function __construct()
    {
        // Initialize container
        $this->container = new Container();
        
        // Initialize database connection
        $this->db = (new Database())->connect();
        
        // Initialize cache
        $this->cache = new FileCache();
        
        // Register services in container
        $this->registerServices();
        
        // Initialize router
        $this->router = new Router();
        
        // Load routes
        $this->loadRoutes();
        
        // Load dependencies
        $this->loadDependencies();
        
        // Load libraries
        $this->loadLibraries();
        
        // Load helpers
        $this->loadHelpers();
        
        // Handle request
        $this->handleRequest();
    }

    /**
     * Register services in the container
     */
    private function registerServices(): void
    {
        // Register database as singleton
        $this->container->singleton(Medoo::class, function () {
            return $this->db;
        });
        
        // Register cache as singleton
        $this->container->singleton(CacheInterface::class, function () {
            return $this->cache;
        });
        
        // Register router
        $this->container->singleton(Router::class, function () {
            return $this->router;
        });

        // Register all services via ServiceProvider
        $serviceProvider = new ServiceProvider($this->container, $this->db, $this->cache);
        $serviceProvider->register();

        // Register event listeners
        $eventServiceProvider = new EventServiceProvider($this->container);
        $eventServiceProvider->register();
    }

    /**
     * Load route definitions
     */
    private function loadRoutes(): void
    {
        $routesFile = __DIR__ . '/../routes/web.php';
        
        if (file_exists($routesFile)) {
            $routes = require $routesFile;
            $routes($this->router);
        }
    }

    /**
     * Handle incoming request
     */
    private function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $this->parseUri();
        
        // Dispatch route
        $route = $this->router->dispatch($method, $uri);
        
        if ($route === null) {
            // Fallback to legacy routing for backward compatibility
            $this->handleLegacyRouting();
            return;
        }
        
        // Build middleware pipeline
        $middleware = new MiddlewarePipeline($this->container);
        $middleware->add($route->getMiddleware());
        
        // Process request through middleware and execute route
        $response = $middleware->process(
            ['route' => $route, 'uri' => $uri, 'method' => $method],
            function ($request) use ($route) {
                return $this->executeRoute($route);
            }
        );
        
        if ($response !== null) {
            echo $response;
        }
    }

    /**
     * Execute route action
     *
     * @param \KenDeNigerian\Krak\core\Route $route
     * @return mixed
     */
    private function executeRoute(\KenDeNigerian\Krak\core\Route $route): mixed
    {
        $action = $route->getAction();
        
        if ($action instanceof \Closure) {
            return $action($route->getParameters());
        }
        
        if (is_string($action)) {
            // Format: "Controller@method"
            if (strpos($action, '@') !== false) {
                [$controllerName, $methodName] = explode('@', $action);
            } else {
                $controllerName = $action;
                $methodName = 'index';
            }
            
            // Convert controller name to lowercase to match actual class names
            $controllerName = strtolower($controllerName);
            
            return $this->executeController($controllerName, $methodName, $route->getParameters());
        }
        
        if (is_array($action)) {
            [$controllerName, $methodName] = $action;
            // Convert controller name to lowercase to match actual class names
            $controllerName = strtolower($controllerName);
            return $this->executeController($controllerName, $methodName, $route->getParameters());
        }
        
        return null;
    }

    /**
     * Execute controller method
     *
     * @param string $controllerName
     * @param string $methodName
     * @param array $parameters
     * @return mixed
     */
    private function executeController(string $controllerName, string $methodName, array $parameters): mixed
    {
        $controllerFile = __DIR__ . '/../controllers/' . strtolower($controllerName) . '.php';
        
        if (!file_exists($controllerFile)) {
            return $this->render404();
        }
        
        require_once($controllerFile);
        
        $className = 'KenDeNigerian\Krak\controllers\\' . strtolower($controllerName);
        
        if (!class_exists($className)) {
            return $this->render404();
        }
        
        // Build URL array for backward compatibility
        $url = array_merge([strtolower($controllerName), $methodName], array_values($parameters));
        
        // Instantiate controller with container
        $controller = new $className($this->db, $url, $this->container);
        
        if (!method_exists($controller, $methodName)) {
            return $this->render404();
        }
        
        // Call controller method
        // Convert associative array to indexed array for method parameters
        $methodParams = array_values($parameters);
        $data = call_user_func_array([$controller, $methodName], $methodParams);
        
        // Run controller
        $controller->run($data);
        
        return null;
    }

    /**
     * Handle legacy routing for backward compatibility
     */
    private function handleLegacyRouting(): void
    {
        $url = [];
        
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));
        }
        
        if (empty($url) || empty($url[0])) {
            $url = ['home'];
        }
        
        $controllerName = $url[0];
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            echo $this->render404();
            return;
        }
        
        require_once($controllerFile);
        
        $class = 'KenDeNigerian\Krak\controllers\\' . strtolower($controllerName);
        $controller = new $class($this->db, $url, $this->container);
        
        $method = $url[1] ?? 'index';
        
        if (!method_exists($controller, $method)) {
            echo $this->render404();
            return;
        }
        
        $data = call_user_func_array([$controller, $method], $url);
        $controller->run($data);
    }

    /**
     * Parse URI from request
     *
     * @return string
     */
    private function parseUri(): string
    {
        if (isset($_GET['url'])) {
            return '/' . trim($_GET['url'], '/');
        }
        
        return '/';
    }

    /**
     * Render 404 error page
     *
     * @return string
     */
    private function render404(): string
    {
        http_response_code(404);
        return $this->render('error/404');
    }

    /**
     * Load Dependencies
     */
    private function loadDependencies(): void
    {
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once(__DIR__ . '/../../vendor/autoload.php');
        }
    }

    /**
     * Load Libraries
     */
    private function loadLibraries(): void
    {
        spl_autoload_register(function ($class) {
            $className = explode('\\', $class);
            if (file_exists(__DIR__ . '/../libraries/' . end($className) . '.php')) {
                require_once(__DIR__ . '/../libraries/' . end($className) . '.php');
            }
        });
    }

    /**
     * Load Helpers
     */
    private function loadHelpers(): void
    {
        if ($handle = opendir(__DIR__ . '/../helpers/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && str_ends_with($entry, '.php')) {
                    require_once(__DIR__ . '/../helpers/' . $entry);
                }
            }
            closedir($handle);
        }
    }

    /**
     * Render a view template.
     *
     * @param string|null $view The file path / name of the view.
     * @return string The rendered view content.
     */
    public function render(string $view = null): string
    {
        ob_start();
        require sprintf('%s/../../%s/%s/views/%s.php', __DIR__, PUBLIC_PATH, THEME_PATH, $view);
        return ob_get_clean();
    }

    /**
     * Returns the base URL of the site.
     *
     * @return string The base URL of the site.
     */
    public function siteUrl(): string
    {
        return URL_PATH;
    }

    /**
     * Returns the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    public function themePath(): string
    {
        return THEME_PATH;
    }
}
