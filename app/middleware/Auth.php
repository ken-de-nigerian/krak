<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\middleware;

use KenDeNigerian\Krak\core\Middleware\MiddlewareInterface;
use Closure;

/**
 * Authentication Middleware
 * Implements MiddlewareInterface following OCP principle
 */
class Auth implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(mixed $request, Closure $next): mixed
    {
        $user = $this->library('User');
        
        if (!$user->isLoggedIn()) {
            redirect('login');
            return '';
        }

        $userData = $user->data();
        
        if (isset($userData['status']) && $userData['status'] == 2) {
            redirect('blocked');
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

