<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Cache;

/**
 * Cache Interface
 * Implements OCP principle
 */
interface CacheInterface
{
    /**
     * Get a value from cache
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Set a value in cache
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Time to live in seconds
     * @return bool
     */
    public function set(string $key, mixed $value, ?int $ttl = null): bool;

    /**
     * Check if a key exists in cache
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Delete a key from cache
     *
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Clear all cache
     *
     * @return bool
     */
    public function clear(): bool;

    /**
     * Get multiple values from cache
     *
     * @param array<string> $keys
     * @return array<string, mixed>
     */
    public function getMultiple(array $keys): array;

    /**
     * Set multiple values in cache
     *
     * @param array<string, mixed> $values
     * @param int|null $ttl
     * @return bool
     */
    public function setMultiple(array $values, ?int $ttl = null): bool;

    /**
     * Delete multiple keys from cache
     *
     * @param array<string> $keys
     * @return bool
     */
    public function deleteMultiple(array $keys): bool;
}

