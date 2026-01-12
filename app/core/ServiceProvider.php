<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use KenDeNigerian\Krak\core\Cache\CacheInterface;
use KenDeNigerian\Krak\core\Cache\FileCache;
use KenDeNigerian\Krak\core\ORM\UserModel;
use KenDeNigerian\Krak\core\ORM\InvestmentModel;
use KenDeNigerian\Krak\core\ORM\DepositModel;
use KenDeNigerian\Krak\core\ORM\WithdrawalModel;
use KenDeNigerian\Krak\core\ORM\PlanModel;
use KenDeNigerian\Krak\core\ORM\SettingsModel;
use KenDeNigerian\Krak\repositories\UserRepository;
use KenDeNigerian\Krak\repositories\SettingsRepository;
use KenDeNigerian\Krak\repositories\InvestmentRepository;
use KenDeNigerian\Krak\repositories\DepositRepository;
use KenDeNigerian\Krak\repositories\WithdrawalRepository;
use KenDeNigerian\Krak\services\UserService;
use KenDeNigerian\Krak\services\SettingsService;
use KenDeNigerian\Krak\services\InvestmentService;
use KenDeNigerian\Krak\services\PlanService;
use KenDeNigerian\Krak\services\TransactionService;
use Medoo\Medoo;

/**
 * Service Provider
 * Registers all services in the container
 */
class ServiceProvider
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * @var Medoo
     */
    private Medoo $db;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param Container $container
     * @param Medoo $db
     * @param CacheInterface $cache
     */
    public function __construct(Container $container, Medoo $db, CacheInterface $cache)
    {
        $this->container = $container;
        $this->db = $db;
        $this->cache = $cache;
    }

    /**
     * Register all services
     *
     * @return void
     */
    public function register(): void
    {
        // Register core services
        $this->container->singleton(Medoo::class, fn() => $this->db);
        $this->container->singleton(CacheInterface::class, fn() => $this->cache);

        // Register ORM Models
        $this->container->bind(UserModel::class, fn() => new UserModel($this->db, $this->cache));
        $this->container->bind(InvestmentModel::class, fn() => new InvestmentModel($this->db, $this->cache));
        $this->container->bind(DepositModel::class, fn() => new DepositModel($this->db, $this->cache));
        $this->container->bind(WithdrawalModel::class, fn() => new WithdrawalModel($this->db, $this->cache));
        $this->container->bind(PlanModel::class, fn() => new PlanModel($this->db, $this->cache));
        $this->container->bind(SettingsModel::class, fn() => new SettingsModel($this->db, $this->cache));

        // Register Repositories
        $this->container->singleton(UserRepository::class, function () {
            $model = $this->container->get(UserModel::class);
            return new UserRepository($model, $this->cache);
        });

        $this->container->singleton(SettingsRepository::class, function () {
            $model = $this->container->get(SettingsModel::class);
            return new SettingsRepository($model, $this->cache);
        });

        $this->container->singleton(InvestmentRepository::class, function () {
            $model = $this->container->get(InvestmentModel::class);
            return new InvestmentRepository($model, $this->cache);
        });

        $this->container->singleton(DepositRepository::class, function () {
            $model = $this->container->get(DepositModel::class);
            return new DepositRepository($model, $this->cache);
        });

        $this->container->singleton(WithdrawalRepository::class, function () {
            $model = $this->container->get(WithdrawalModel::class);
            return new WithdrawalRepository($model, $this->cache);
        });

        // Register Services
        $this->container->singleton(UserService::class, function () {
            $userRepo = $this->container->get(UserRepository::class);
            return new UserService($userRepo, $this->cache);
        });

        $this->container->singleton(SettingsService::class, function () {
            $settingsRepo = $this->container->get(SettingsRepository::class);
            return new SettingsService($settingsRepo, $this->cache);
        });

        $this->container->singleton(InvestmentService::class, function () {
            $investmentRepo = $this->container->get(InvestmentRepository::class);
            return new InvestmentService($investmentRepo, $this->cache);
        });

        $this->container->singleton(PlanService::class, function () {
            return new PlanService($this->db, $this->cache);
        });

        $this->container->singleton(TransactionService::class, function () {
            return new TransactionService($this->db, $this->cache);
        });
    }
}

