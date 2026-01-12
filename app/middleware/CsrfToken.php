<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\middleware;

use KenDeNigerian\Krak\core\Middleware\MiddlewareInterface;
use Closure;

/**
 * CSRF Token Middleware
 * Implements MiddlewareInterface following OCP principle
 */
class CsrfToken implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(mixed $request, Closure $next): mixed
    {
        $this->generateToken();
        
        if (!$this->validateToken()) {
            return $this->errorResponse();
        }
        
        return $next($request);
    }

    /**
     * Generate and set a random token
     */
    private function generateToken(): void
    {
        if (empty($_SESSION['token_id'])) {
            $token_id = hash('sha256', substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));
            $_SESSION['token_id'] = $token_id;
        }
    }

    /**
     * Validate the CSRF token
     *
     * @return bool
     */
    private function validateToken(): bool
    {
        if (empty($_POST)) {
            return true; // No POST data, skip validation
        }

        if (!isset($_POST['token_id']) || $_POST['token_id'] != $_SESSION['token_id']) {
            return false;
        }

        return true;
    }

    /**
     * Return error response
     *
     * @return string
     */
    private function errorResponse(): string
    {
        $response = [
            'status' => 'error',
            'message' => 'CsrfToken validation failed. Please refresh the page and try again.',
        ];

        header('Content-Type: application/json');
        http_response_code(403);
        return json_encode($response);
    }
}
