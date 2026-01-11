<?php

namespace Fir;

use Medoo\Medoo;
use mysqli;

/**
 * The app router which decides what router and method are selected based on the user's input
 */
class App
{
    /**
     * List of GET parameters sent by the user
     * @var array
     */
    protected array $url = [];

    /**
     * Database connection property
     * @var mysqli|null|Medoo
     */
    protected mysqli|null|Medoo $db;

    /**
     * @var mixed|string
     */
    private mixed $controller;

    /**
     * @var mixed|string
     */
    private mixed $method;

    /**
     * App constructor.
     */
    public function __construct()
    {
        // Create the database connection
        $this->db = (new Connection\Database())->connect();

        // Load dependencies
        $this->loadDependencies();

        // Load libraries
        $this->loadLibraries();

        // Load helpers
        $this->loadHelpers();

        // Instantiate the middleware
        new Middleware\Middleware();

        // Parse the URL
        $this->parseUrl();

        // Set default controller if no controller is specified
        if (empty($this->url) || empty($this->url[0])) {
            $this->url = ['home']; // Default controller
        }

        // Check if the controller exists
        $controllerName = $this->url[0];
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            // Controller not found, render _404 controller
            echo $this->render404(); // Output the rendered page 404
            return;
        }

        // Require the controller file based on the URL
        require_once($controllerFile);

        // Determine the controller class name
        $class = 'Fir\Controllers\\' . ucfirst($controllerName);

        // Instantiate the controller class
        $this->controller = new $class($this->db, $this->url);

        // Check if a method is specified
        if (isset($this->url[1])) {
            $methodName = $this->url[1];
            if (!method_exists($this->controller, $methodName)) {
                // Method not found, render _404 controller
                echo $this->render404(); // Output the rendered page 404
                return;
            }
            $this->method = $methodName;
        } else {
            // Default method if not specified
            $this->method = 'index';
        }

        // Call the method from the controller and pass the params
        $data = call_user_func_array([$this->controller, $this->method], $this->url);

        // Run the controller
        $this->controller->run($data);
    }

    /**
     * Render _ controller 404
     *
     * This method is responsible for rendering the 404 error page.
     *
     * @return string The rendered 404 error page content.
     */
    private function render404(): string
    {
        // Set HTTP response code to 404
        http_response_code(404);

        // Render the 404 error page
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
        // Autoload any needed library
        spl_autoload_register(function ($class) {
            // Explode the class namespace and select only the class name
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
     * Parse and set the GET parameters sent by the user
     */
    public function parseUrl(): void
    {
        if (isset($_GET['url'])) {
            $this->url = explode('/', rtrim($_GET['url'], '/'));
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