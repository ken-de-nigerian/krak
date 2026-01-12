<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\repositories;

use KenDeNigerian\Krak\core\Repository\BaseRepository;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Settings Repository
 */
class SettingsRepository extends BaseRepository
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
     * Get settings with caching
     *
     * @return array
     */
    public function getSettings(): array
    {
        $cacheKey = 'settings.all';
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $settings = $this->model->find(1);
        
        if ($settings && $this->cache) {
            $this->cache->set($cacheKey, $settings, 3600);
        }

        return $settings ?? [];
    }

    /**
     * Get livechat extensions
     *
     * @return array
     */
    public function getLivechat(): array
    {
        $cacheKey = 'extensions.livechat';
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $extensions = $this->model->db->get('extensions', '*', [
            'id' => 1,
            'status' => 1
        ]);

        if ($extensions && $this->cache) {
            $this->cache->set($cacheKey, $extensions, 1800);
        }

        return $extensions ?? [];
    }

    /**
     * Get WhatsApp extensions
     *
     * @return array
     */
    public function getWhatsapp(): array
    {
        $cacheKey = 'extensions.whatsapp';
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $extensions = $this->model->db->get('extensions', '*', [
            'id' => 2,
            'status' => 1
        ]);

        if ($extensions && $this->cache) {
            $this->cache->set($cacheKey, $extensions, 1800);
        }

        return $extensions ?? [];
    }

    /**
     * Get gateways
     *
     * @return array
     */
    public function getGateways(): array
    {
        $cacheKey = 'gateways.all';
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        try {
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);
            if (!is_array($wallets)) {
                return [];
            }
            usort($wallets, fn($a, $b) => $a['status'] <=> $b['status']);
            
            if ($this->cache) {
                $this->cache->set($cacheKey, $wallets, 1800);
            }
            
            return $wallets;
        } catch (\Exception $e) {
            error_log('Error in getGateways(): ' . $e->getMessage());
            return [];
        }
    }
}

