<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

/**
 * Relationship class for handling model relationships
 * Prevents N+1 queries through eager loading
 */
class Relationship
{
    /**
     * @var Model
     */
    private Model $related;

    /**
     * @var string
     */
    private string $foreignKey;

    /**
     * @var string
     */
    private string $localKey;

    /**
     * @var string
     */
    private string $type;

    /**
     * @param Model $related
     * @param string $foreignKey
     * @param string $localKey
     * @param string $type
     */
    public function __construct(Model $related, string $foreignKey, string $localKey, string $type)
    {
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
        $this->type = $type;
    }

    /**
     * Eager load relationships to prevent N+1 queries
     *
     * @param array $records
     * @return array
     */
    public function eagerLoad(array $records): array
    {
        if (empty($records)) {
            return $records;
        }

        if ($this->type === 'belongsTo') {
            return $this->eagerLoadBelongsTo($records);
        } elseif ($this->type === 'hasMany') {
            return $this->eagerLoadHasMany($records);
        }

        return $records;
    }

    /**
     * Eager load belongs to relationships
     *
     * @param array $records
     * @return array
     */
    private function eagerLoadBelongsTo(array $records): array
    {
        // Extract all foreign keys
        $foreignKeys = array_filter(array_column($records, $this->foreignKey));
        
        if (empty($foreignKeys)) {
            return $records;
        }

        // Load all related records in one query
        $relatedRecords = $this->related->findAll([
            $this->localKey => array_unique($foreignKeys)
        ]);

        // Create a map for quick lookup
        $map = [];
        foreach ($relatedRecords as $related) {
            $map[$related[$this->localKey]] = $related;
        }

        // Attach related records to parent records
        foreach ($records as &$record) {
            $key = $record[$this->foreignKey] ?? null;
            $record[$this->getRelationName()] = $map[$key] ?? null;
        }

        return $records;
    }

    /**
     * Eager load has many relationships
     *
     * @param array $records
     * @return array
     */
    private function eagerLoadHasMany(array $records): array
    {
        // Extract all local keys
        $localKeys = array_filter(array_column($records, $this->localKey));
        
        if (empty($localKeys)) {
            return $records;
        }

        // Load all related records in one query
        $relatedRecords = $this->related->findAll([
            $this->foreignKey => array_unique($localKeys)
        ]);

        // Group related records by foreign key
        $grouped = [];
        foreach ($relatedRecords as $related) {
            $key = $related[$this->foreignKey];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $related;
        }

        // Attach related records to parent records
        foreach ($records as &$record) {
            $key = $record[$this->localKey] ?? null;
            $record[$this->getRelationName()] = $grouped[$key] ?? [];
        }

        return $records;
    }

    /**
     * Get relation name from foreign key
     *
     * @return string
     */
    private function getRelationName(): string
    {
        return str_replace('_id', '', $this->foreignKey);
    }

    /**
     * Get related model
     *
     * @return Model
     */
    public function getRelated(): Model
    {
        return $this->related;
    }
}

