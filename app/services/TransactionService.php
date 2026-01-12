<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\services;

use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\Cache\CacheInterface;
use Medoo\Medoo;

/**
 * Transaction Service
 * Handles business logic for transactions
 */
class TransactionService
{
    /**
     * @var Model
     */
    private Model $transactionModel;

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
        $this->transactionModel = new class($db, $cache) extends Model {
            protected string $table = 'transactions';
            protected string $primaryKey = 'id';
        };
        $this->cache = $cache;
    }

    /**
     * Get transactions for user with pagination
     *
     * @param string $userId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getTransactionsByUserId(string $userId, int $page = 1, int $limit = 5): array
    {
        $cacheKey = "transactions.user.{$userId}.{$page}.{$limit}";
        
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $offset = ($page - 1) * $limit;
        
        $transactions = $this->transactionModel->findAll(
            ['userid' => $userId],
            ['*'],
            [
                'ORDER' => ['id' => 'DESC'],
                'LIMIT' => [$offset, $limit]
            ]
        );

        if ($this->cache) {
            $this->cache->set($cacheKey, $transactions, 60); // Cache for 1 minute
        }

        return $transactions;
    }

    /**
     * Get recent transactions for user
     *
     * @param string $userId
     * @param int $limit
     * @return array
     */
    public function getRecentTransactions(string $userId, int $limit = 5): array
    {
        return $this->getTransactionsByUserId($userId, 1, $limit);
    }
}

