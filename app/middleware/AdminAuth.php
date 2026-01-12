<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\middleware;

use KenDeNigerian\Krak\core\Middleware\MiddlewareInterface;
use Closure;

/**
 * Admin Authentication Middleware
 * Implements MiddlewareInterface following OCP principle
 */
class AdminAuth implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(mixed $request, Closure $next): mixed
    {
        $admin = $this->library('Admin');
        
        if (!$admin->isLoggedIn()) {
            redirect('admin/login');
            return '';
        }

        return $next($request);
    }

    /**
     * Get library instance (for backward compatibility)
     *
     * @param string $library
     * @return object
     */
    private function library(string $library): object
    {
        require_once(__DIR__ . '/../libraries/' . $library . '.php');
        $class = 'KenDeNigerian\Krak\libraries\\' . $library;
        $db = (new \KenDeNigerian\Krak\core\Database())->connect();
        return new $class($db);
    }
}

