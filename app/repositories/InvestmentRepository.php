<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\repositories;

use KenDeNigerian\Krak\core\Repository\BaseRepository;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Investment Repository
 */
class InvestmentRepository extends BaseRepository
{
    /**
     * @param Model $model
     * @param CacheInterface|null $cache
     */
    public function __construct(Model $model, ?CacheInterface $cache = null)
    {
        parent::__construct($model, $cache);
    }

    /**
     * Get investments by user ID with eager loading
     *
     * @param string $userId
     * @param int $limit
     * @return array
     */
    public function getByUserId(string $userId, int $limit = 10): array
    {
        $cacheKey = "investments.user.{$userId}.{$limit}";
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $investments = $this->model->findAll(
            ['userid' => $userId],
            ['*'],
            [
                'ORDER' => ['initiated_at' => 'DESC'],
                'LIMIT' => $limit
            ]
        );

        if ($investments && $this->cache) {
            $this->cache->set($cacheKey, $investments, 300);
        }

        return $investments;
    }

    /**
     * Count running investments
     *
     * @return int
     */
    public function countRunning(): int
    {
        $cacheKey = 'investments.count.running';
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return (int) $this->cache->get($cacheKey);
        }

        $count = $this->model->count(['status' => 2]);

        if ($this->cache) {
            $this->cache->set($cacheKey, $count, 60);
        }

        return $count;
    }
}

