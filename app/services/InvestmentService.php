<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\services;

use KenDeNigerian\Krak\repositories\InvestmentRepository;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Investment Service
 */
class InvestmentService
{
    /**
     * @var InvestmentRepository
     */
    private InvestmentRepository $investmentRepository;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param InvestmentRepository $investmentRepository
     * @param CacheInterface $cache
     */
    public function __construct(InvestmentRepository $investmentRepository, CacheInterface $cache)
    {
        $this->investmentRepository = $investmentRepository;
        $this->cache = $cache;
    }

    /**
     * Get investments by user ID
     *
     * @param string $userId
     * @param int $limit
     * @return array
     */
    public function getByUserId(string $userId, int $limit = 10): array
    {
        return $this->investmentRepository->getByUserId($userId, $limit);
    }

    /**
     * Count running investments
     *
     * @return int
     */
    public function countRunning(): int
    {
        return $this->investmentRepository->countRunning();
    }
}

