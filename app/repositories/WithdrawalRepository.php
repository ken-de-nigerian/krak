<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\repositories;

use KenDeNigerian\Krak\core\Repository\BaseRepository;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Withdrawal Repository
 */
class WithdrawalRepository extends BaseRepository
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
     * Get total withdrawals for user
     *
     * @param string $userId
     * @return float
     */
    public function getTotalByUserId(string $userId): float
    {
        $cacheKey = "withdrawals.total.{$userId}";
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return (float) $this->cache->get($cacheKey);
        }

        $sum = $this->model->db->sum('withdrawals', 'amount', [
            'userid' => $userId,
            'status' => [1]
        ]);

        $total = (float) $sum;

        if ($this->cache) {
            $this->cache->set($cacheKey, $total, 300);
        }

        return $total;
    }
}

