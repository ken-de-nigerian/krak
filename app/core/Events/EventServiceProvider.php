<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Events;

use KenDeNigerian\Krak\core\Container;

/**
 * Event Service Provider
 * Registers event listeners
 */
class EventServiceProvider
{
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register event listeners
     *
     * @return void
     */
    public function register(): void
    {
        // Example: Register user events
        EventDispatcher::listen('user.created', function ($user) {
            // Send welcome email, log, etc.
            error_log("User created: {$user['userid']}");
        });

        EventDispatcher::listen('user.updated', function ($user) {
            // Clear cache, log, etc.
            error_log("User updated: {$user['userid']}");
        });

        EventDispatcher::listen('investment.completed', function ($investment) {
            // Process completion, send notification, etc.
            error_log("Investment completed: {$investment['investId']}");
        });

        EventDispatcher::listen('deposit.completed', function ($deposit) {
            // Update balance, send notification, etc.
            error_log("Deposit completed: {$deposit['id']}");
        });
    }
}

