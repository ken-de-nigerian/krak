<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Repository;

/**
 * Repository Interface
 * Implements OCP principle
 */
interface RepositoryInterface
{
    /**
     * Find a record by ID
     *
     * @param int|string $id
     * @return array|null
     */
    public function find(int|string $id): ?array;

    /**
     * Find all records
     *
     * @param array $conditions
     * @return array
     */
    public function findAll(array $conditions = []): array;

    /**
     * Create a new record
     *
     * @param array<string, mixed> $data
     * @return int|string|null
     */
    public function create(array $data): int|string|null;

    /**
     * Update a record
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(int|string $id, array $data): bool;

    /**
     * Delete a record
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool;
}

