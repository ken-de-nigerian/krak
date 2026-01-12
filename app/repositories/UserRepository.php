<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\repositories;

use KenDeNigerian\Krak\core\Repository\BaseRepository;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * User Repository
 * Implements SRP - handles only user data access
 */
class UserRepository extends BaseRepository
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
     * Find user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        $cacheKey = "user.email.{$email}";
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $users = $this->model->findAll(['email' => $email], ['*'], ['LIMIT' => 1]);
        $user = $users[0] ?? null;

        if ($user && $this->cache) {
            $this->cache->set($cacheKey, $user, 3600);
        }

        return $user;
    }

    /**
     * Find user by username
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        $cacheKey = "user.username.{$username}";
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $users = $this->model->findAll(['username' => $username], ['*'], ['LIMIT' => 1]);
        $user = $users[0] ?? null;

        if ($user && $this->cache) {
            $this->cache->set($cacheKey, $user, 3600);
        }

        return $user;
    }

    /**
     * Get user deposits total
     *
     * @param string $userId
     * @return float
     */
    public function getDepositsTotal(string $userId): float
    {
        $cacheKey = "user.deposits.{$userId}";
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return (float) $this->cache->get($cacheKey);
        }

        // This would use a proper query builder in production
        $sum = $this->model->db->sum('deposits', 'amount', [
            'userid' => $userId,
            'status' => [1]
        ]);

        $total = (float) $sum;

        if ($this->cache) {
            $this->cache->set($cacheKey, $total, 300); // Cache for 5 minutes
        }

        return $total;
    }
}

