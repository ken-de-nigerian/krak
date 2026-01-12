<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Events;

/**
 * Event Dispatcher
 * Implements Observer pattern for events
 */
class EventDispatcher
{
    /**
     * @var array<string, array<callable>>
     */
    private static array $listeners = [];

    /**
     * Register an event listener
     *
     * @param string $event
     * @param callable $listener
     * @return void
     */
    public static function listen(string $event, callable $listener): void
    {
        if (!isset(self::$listeners[$event])) {
            self::$listeners[$event] = [];
        }
        
        self::$listeners[$event][] = $listener;
    }

    /**
     * Dispatch an event
     *
     * @param string $event
     * @param mixed $payload
     * @return void
     */
    public static function dispatch(string $event, mixed $payload = null): void
    {
        if (!isset(self::$listeners[$event])) {
            return;
        }
        
        foreach (self::$listeners[$event] as $listener) {
            call_user_func($listener, $payload);
        }
    }

    /**
     * Remove all listeners for an event
     *
     * @param string $event
     * @return void
     */
    public static function forget(string $event): void
    {
        unset(self::$listeners[$event]);
    }

    /**
     * Get all listeners for an event
     *
     * @param string $event
     * @return array<callable>
     */
    public static function getListeners(string $event): array
    {
        return self::$listeners[$event] ?? [];
    }

    /**
     * Check if event has listeners
     *
     * @param string $event
     * @return bool
     */
    public static function hasListeners(string $event): bool
    {
        return isset(self::$listeners[$event]) && !empty(self::$listeners[$event]);
    }
}

