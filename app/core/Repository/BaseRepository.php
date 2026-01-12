<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Repository;

use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Base Repository implementation
 * Implements SRP principle
 */
abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var CacheInterface|null
     */
    protected ?CacheInterface $cache = null;

    /**
     * @param Model $model
     * @param CacheInterface|null $cache
     */
    public function __construct(Model $model, ?CacheInterface $cache = null)
    {
        $this->model = $model;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function find(int|string $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(array $conditions = []): array
    {
        return $this->model->findAll($conditions);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data): int|string|null
    {
        return $this->model->create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update(int|string $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(int|string $id): bool
    {
        return $this->model->delete($id);
    }
}

