<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\middleware;

use KenDeNigerian\Krak\core\Middleware\MiddlewareInterface;
use Closure;

/**
 * Maintenance Mode Middleware
 */
class Maintenance implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(mixed $request, Closure $next): mixed
    {
        $maintenanceModel = $this->getModel('Maintenance');
        $underMaintenance = $maintenanceModel->underMaintenance();

        if (isset($underMaintenance['maintenance_mode']) && $underMaintenance['maintenance_mode'] == 1) {
            redirect('maintenance');
            return '';
        }

        return $next($request);
    }

    /**
     * Get model instance
     *
     * @param string $model
     * @return object
     */
    private function getModel(string $model): object
    {
        require_once(__DIR__ . '/../models/' . $model . '.php');
        $class = 'KenDeNigerian\Krak\models\\' . $model;
        $db = (new \KenDeNigerian\Krak\core\Database())->connect();
        return new $class($db);
    }
}

