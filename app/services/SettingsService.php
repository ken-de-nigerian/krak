<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\services;

use KenDeNigerian\Krak\repositories\SettingsRepository;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Settings Service
 */
class SettingsService
{
    /**
     * @var SettingsRepository
     */
    private SettingsRepository $settingsRepository;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param SettingsRepository $settingsRepository
     * @param CacheInterface $cache
     */
    public function __construct(SettingsRepository $settingsRepository, CacheInterface $cache)
    {
        $this->settingsRepository = $settingsRepository;
        $this->cache = $cache;
    }

    /**
     * Get all settings
     *
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settingsRepository->getSettings();
    }

    /**
     * Get livechat extensions
     *
     * @return array
     */
    public function getLivechat(): array
    {
        return $this->settingsRepository->getLivechat();
    }

    /**
     * Get WhatsApp extensions
     *
     * @return array
     */
    public function getWhatsapp(): array
    {
        return $this->settingsRepository->getWhatsapp();
    }

    /**
     * Get gateways
     *
     * @return array
     */
    public function getGateways(): array
    {
        return $this->settingsRepository->getGateways();
    }
}

