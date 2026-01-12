<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\services;

use KenDeNigerian\Krak\repositories\UserRepository;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * User Service
 * Implements SRP - handles user business logic
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param UserRepository $userRepository
     * @param CacheInterface $cache
     */
    public function __construct(UserRepository $userRepository, CacheInterface $cache)
    {
        $this->userRepository = $userRepository;
        $this->cache = $cache;
    }

    /**
     * Get user by ID
     *
     * @param string $userId
     * @return array|null
     */
    public function getUserById(string $userId): ?array
    {
        return $this->userRepository->find($userId);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return array|null
     */
    public function getUserByEmail(string $email): ?array
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Get user deposits total
     *
     * @param string $userId
     * @return float
     */
    public function getDepositsTotal(string $userId): float
    {
        return $this->userRepository->getDepositsTotal($userId);
    }

    /**
     * Get user with related data (prevents N+1)
     *
     * @param string $userId
     * @return array|null
     */
    public function getUserWithRelations(string $userId): ?array
    {
        $cacheKey = "user.relations.{$userId}";
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $user = $this->userRepository->find($userId);
        
        if (!$user) {
            return null;
        }

        // Eager load related data to prevent N+1 queries
        // In a real implementation, this would use the ORM's eager loading
        $user['deposits_total'] = $this->getDepositsTotal($userId);
        $user['withdrawals_total'] = $this->getWithdrawalsTotal($userId);
        $user['investments_total'] = $this->getInvestmentsTotal($userId);

        $this->cache->set($cacheKey, $user, 300);

        return $user;
    }

    /**
     * Get withdrawals total
     *
     * @param string $userId
     * @return float
     */
    private function getWithdrawalsTotal(string $userId): float
    {
        $cacheKey = "user.withdrawals.{$userId}";
        
        if ($this->cache->has($cacheKey)) {
            return (float) $this->cache->get($cacheKey);
        }

        // Use repository pattern - would need WithdrawalRepository
        $sum = $this->userRepository->model->db->sum('withdrawals', 'amount', [
            'userid' => $userId,
            'status' => [1]
        ]);

        $total = (float) $sum;

        $this->cache->set($cacheKey, $total, 300);

        return $total;
    }

    /**
     * Get investments total
     *
     * @param string $userId
     * @return float
     */
    private function getInvestmentsTotal(string $userId): float
    {
        $cacheKey = "user.investments.{$userId}";
        
        if ($this->cache->has($cacheKey)) {
            return (float) $this->cache->get($cacheKey);
        }

        // Use repository pattern - would need InvestmentRepository
        $sum = $this->userRepository->model->db->sum('invests', 'amount', [
            'userid' => $userId,
            'status' => [1]
        ]);

        $total = (float) $sum;

        $this->cache->set($cacheKey, $total, 300);

        return $total;
    }
}

