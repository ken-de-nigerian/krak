<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Middleware;

use Closure;

/**
 * Middleware Interface
 * Implements OCP principle
 */
interface MiddlewareInterface
{
    /**
     * Handle an incoming request
     *
     * @param mixed $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(mixed $request, Closure $next): mixed;
}

