<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use Medoo\Medoo;
use KenDeNigerian\Krak\core\Cache\CacheInterface;

/**
 * Base ORM Model class
 * Implements SRP and OCP principles with relationship support and N+1 prevention
 */
abstract class Model
{
    /**
     * @var Medoo
     */
    protected Medoo $db;

    /**
     * @var CacheInterface|null
     */
    protected ?CacheInterface $cache = null;

    /**
     * Table name
     *
     * @var string
     */
    protected string $table;

    /**
     * Primary key
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Fillable attributes
     *
     * @var array<string>
     */
    protected array $fillable = [];

    /**
     * Hidden attributes
     *
     * @var array<string>
     */
    protected array $hidden = [];

    /**
     * Relationships cache
     *
     * @var array<string, mixed>
     */
    private array $relationships = [];

    /**
     * Eager loaded relationships
     *
     * @var array<string>
     */
    protected array $with = [];

    /**
     * @param Medoo $db
     * @param CacheInterface|null $cache
     */
    public function __construct(Medoo $db, ?CacheInterface $cache = null)
    {
        $this->db = $db;
        $this->cache = $cache;
        
        if (empty($this->table)) {
            $this->table = $this->guessTableName();
        }
    }

    /**
     * Guess table name from class name
     *
     * @return string
     */
    private function guessTableName(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';
    }

    /**
     * Find a record by ID
     *
     * @param int|string $id
     * @param array<string> $columns
     * @return array|null
     */
    public function find(int|string $id, array $columns = ['*']): ?array
    {
        $cacheKey = $this->getCacheKey("find.{$id}." . implode(',', $columns));
        
        if ($this->cache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $columns = $columns === ['*'] ? '*' : $columns;
        $record = $this->db->get($this->table, $columns, [$this->primaryKey => $id]);

        if ($record && $this->cache) {
            $this->cache->set($cacheKey, $record, 3600);
        }

        return $record ?: null;
    }

    /**
     * Find multiple records
     *
     * @param array $conditions
     * @param array<string> $columns
     * @param array $options
     * @return array
     */
    public function findAll(array $conditions = [], array $columns = ['*'], array $options = []): array
    {
        $columns = $columns === ['*'] ? '*' : $columns;
        $query = array_merge($conditions, $options);
        
        return $this->db->select($this->table, $columns, $query);
    }

    /**
     * Create a new record
     *
     * @param array<string, mixed> $data
     * @return int|string|null
     */
    public function create(array $data): int|string|null
    {
        $data = $this->filterFillable($data);
        
        $this->db->insert($this->table, $data);
        $id = $this->db->id();
        
        $this->clearCache();
        
        return $id;
    }

    /**
     * Update a record
     *
     * @param int|string $id
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(int|string $id, array $data): bool
    {
        $data = $this->filterFillable($data);
        
        $result = $this->db->update($this->table, $data, [$this->primaryKey => $id]);
        
        $this->clearCache();
        
        return $result->rowCount() > 0;
    }

    /**
     * Delete a record
     *
     * @param int|string $id
     * @return bool
     */
    public function delete(int|string $id): bool
    {
        $result = $this->db->delete($this->table, [$this->primaryKey => $id]);
        
        $this->clearCache();
        
        return $result->rowCount() > 0;
    }

    /**
     * Count records
     *
     * @param array $conditions
     * @return int
     */
    public function count(array $conditions = []): int
    {
        return $this->db->count($this->table, '*', $conditions);
    }

    /**
     * Check if record exists
     *
     * @param array $conditions
     * @return bool
     */
    public function exists(array $conditions): bool
    {
        return $this->db->has($this->table, $conditions);
    }

    /**
     * Define a belongs to relationship
     *
     * @param string $related
     * @param string|null $foreignKey
     * @param string|null $ownerKey
     * @return Relationship
     */
    protected function belongsTo(string $related, ?string $foreignKey = null, ?string $ownerKey = null): Relationship
    {
        $instance = new $related($this->db, $this->cache);
        $foreignKey = $foreignKey ?? $this->guessForeignKey($instance->getTable());
        $ownerKey = $ownerKey ?? $instance->getPrimaryKey();

        return new Relationship($instance, $foreignKey, $ownerKey, 'belongsTo');
    }

    /**
     * Define a has many relationship
     *
     * @param string $related
     * @param string|null $foreignKey
     * @param string|null $localKey
     * @return Relationship
     */
    protected function hasMany(string $related, ?string $foreignKey = null, ?string $localKey = null): Relationship
    {
        $instance = new $related($this->db, $this->cache);
        $foreignKey = $foreignKey ?? $this->guessForeignKey($this->table);
        $localKey = $localKey ?? $this->primaryKey;

        return new Relationship($instance, $foreignKey, $localKey, 'hasMany');
    }

    /**
     * Eager load relationships to prevent N+1 queries
     *
     * @param array $records
     * @param array<string> $relations
     * @return array
     */
    public function eagerLoad(array $records, array $relations): array
    {
        foreach ($relations as $relation) {
            if (method_exists($this, $relation)) {
                $relationship = $this->$relation();
                $records = $relationship->eagerLoad($records);
            }
        }

        return $records;
    }

    /**
     * Guess foreign key name
     *
     * @param string $table
     * @return string
     */
    private function guessForeignKey(string $table): string
    {
        return strtolower($table) . '_id';
    }

    /**
     * Filter fillable attributes
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Get cache key
     *
     * @param string $suffix
     * @return string
     */
    private function getCacheKey(string $suffix): string
    {
        return "model.{$this->table}.{$suffix}";
    }

    /**
     * Clear cache for this model
     *
     * @return void
     */
    protected function clearCache(): void
    {
        if ($this->cache) {
            // Note: FileCache doesn't support wildcard deletion
            // In production, implement cache tags or maintain a list of keys
            // For now, we'll clear specific keys when needed
        }
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get primary key
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}

