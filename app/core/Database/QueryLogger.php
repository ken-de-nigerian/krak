<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Database;

/**
 * Query Logger for detecting N+1 queries
 */
class QueryLogger
{
    /**
     * @var array
     */
    private static array $queries = [];

    /**
     * @var bool
     */
    private static bool $enabled = false;

    /**
     * Enable query logging
     *
     * @return void
     */
    public static function enable(): void
    {
        self::$enabled = true;
        self::$queries = [];
    }

    /**
     * Disable query logging
     *
     * @return void
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }

    /**
     * Log a query
     *
     * @param string $query
     * @param array $params
     * @param float $time
     * @return void
     */
    public static function log(string $query, array $params = [], float $time = 0): void
    {
        if (!self::$enabled) {
            return;
        }

        self::$queries[] = [
            'query' => $query,
            'params' => $params,
            'time' => $time,
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ];
    }

    /**
     * Get all logged queries
     *
     * @return array
     */
    public static function getQueries(): array
    {
        return self::$queries;
    }

    /**
     * Detect potential N+1 queries
     *
     * @return array
     */
    public static function detectNPlusOne(): array
    {
        $similarQueries = [];
        $patterns = [];

        foreach (self::$queries as $query) {
            // Extract table name and basic pattern
            if (preg_match('/FROM\s+(\w+)/i', $query['query'], $matches)) {
                $table = $matches[1];
                $pattern = preg_replace('/\d+/', '?', $query['query']);
                
                if (!isset($patterns[$pattern])) {
                    $patterns[$pattern] = [];
                }
                
                $patterns[$pattern][] = [
                    'table' => $table,
                    'query' => $query['query'],
                    'trace' => $query['trace']
                ];
            }
        }

        // Find patterns that appear multiple times (potential N+1)
        foreach ($patterns as $pattern => $queries) {
            if (count($queries) > 5) {
                $similarQueries[$pattern] = [
                    'count' => count($queries),
                    'queries' => array_slice($queries, 0, 5) // Show first 5
                ];
            }
        }

        return $similarQueries;
    }

    /**
     * Get query statistics
     *
     * @return array
     */
    public static function getStats(): array
    {
        $totalQueries = count(self::$queries);
        $totalTime = array_sum(array_column(self::$queries, 'time'));
        
        $byTable = [];
        foreach (self::$queries as $query) {
            if (preg_match('/FROM\s+(\w+)/i', $query['query'], $matches)) {
                $table = $matches[1];
                if (!isset($byTable[$table])) {
                    $byTable[$table] = 0;
                }
                $byTable[$table]++;
            }
        }

        return [
            'total_queries' => $totalQueries,
            'total_time' => $totalTime,
            'avg_time' => $totalQueries > 0 ? $totalTime / $totalQueries : 0,
            'by_table' => $byTable,
            'n_plus_one' => self::detectNPlusOne()
        ];
    }
}

