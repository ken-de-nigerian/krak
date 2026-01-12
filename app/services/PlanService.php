<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\services;

use KenDeNigerian\Krak\core\ORM\PlanModel;
use KenDeNigerian\Krak\core\Cache\CacheInterface;
use Medoo\Medoo;

/**
 * Plan Service
 * Handles business logic for plans
 */
class PlanService
{
    /**
     * @var PlanModel
     */
    private PlanModel $planModel;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param Medoo $db
     * @param CacheInterface $cache
     */
    public function __construct(Medoo $db, CacheInterface $cache)
    {
        $this->planModel = new PlanModel($db, $cache);
        $this->cache = $cache;
    }

    /**
     * Get all plans
     *
     * @return array
     */
    public function getAllPlans(): array
    {
        $cacheKey = 'plans.all';
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $plans = $this->planModel->findAll([], ['*'], [
            'ORDER' => ['created_at' => 'ASC']
        ]);

        if ($this->cache) {
            $this->cache->set($cacheKey, $plans, 3600);
        }

        return $plans;
    }

    /**
     * Get plan by ID
     *
     * @param int|string $id
     * @return array|null
     */
    public function getPlanById(int|string $id): ?array
    {
        $cacheKey = "plan.{$id}";
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $plan = $this->planModel->find($id);

        if ($plan && $this->cache) {
            $this->cache->set($cacheKey, $plan, 3600);
        }

        return $plan;
    }
}

