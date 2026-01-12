<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use Medoo\Medoo;

/**
 * Query Builder for complex queries
 * Prevents N+1 queries through proper joins
 */
class QueryBuilder
{
    /**
     * @var Medoo
     */
    private Medoo $db;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var array
     */
    private array $select = ['*'];

    /**
     * @var array
     */
    private array $joins = [];

    /**
     * @var array
     */
    private array $where = [];

    /**
     * @var array
     */
    private array $orderBy = [];

    /**
     * @var int|null
     */
    private ?int $limit = null;

    /**
     * @var int|null
     */
    private ?int $offset = null;

    /**
     * @param Medoo $db
     * @param string $table
     */
    public function __construct(Medoo $db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    /**
     * Select columns
     *
     * @param array|string $columns
     * @return self
     */
    public function select(array|string $columns): self
    {
        $this->select = is_array($columns) ? $columns : [$columns];
        return $this;
    }

    /**
     * Add join
     *
     * @param string $table
     * @param string $condition
     * @param string $type
     * @return self
     */
    public function join(string $table, string $condition, string $type = 'INNER'): self
    {
        $this->joins[] = [
            'table' => $table,
            'condition' => $condition,
            'type' => $type
        ];
        return $this;
    }

    /**
     * Add where condition
     *
     * @param string|array $column
     * @param mixed $value
     * @return self
     */
    public function where(string|array $column, mixed $value = null): self
    {
        if (is_array($column)) {
            $this->where = array_merge($this->where, $column);
        } else {
            $this->where[$column] = $value;
        }
        return $this;
    }

    /**
     * Order by
     *
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy[$column] = $direction;
        return $this;
    }

    /**
     * Set limit
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set offset
     *
     * @param int $offset
     * @return self
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Execute query and get results
     *
     * @return array
     */
    public function get(): array
    {
        $options = [];

        if (!empty($this->where)) {
            $options = array_merge($options, $this->where);
        }

        if (!empty($this->orderBy)) {
            $options['ORDER'] = $this->orderBy;
        }

        if ($this->limit !== null) {
            if ($this->offset !== null) {
                $options['LIMIT'] = [$this->offset, $this->limit];
            } else {
                $options['LIMIT'] = $this->limit;
            }
        }

        // If joins are needed, use raw SQL
        if (!empty($this->joins)) {
            return $this->executeWithJoins($options);
        }

        return $this->db->select($this->table, $this->select, $options);
    }

    /**
     * Execute query with joins using raw SQL
     *
     * @param array $options
     * @return array
     */
    private function executeWithJoins(array $options): array
    {
        $select = implode(', ', $this->select);
        $sql = "SELECT {$select} FROM {$this->table}";

        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['condition']}";
        }

        $whereParts = [];
        $params = [];

        foreach ($this->where as $key => $value) {
            if (is_array($value)) {
                $placeholders = implode(',', array_fill(0, count($value), '?'));
                $whereParts[] = "{$key} IN ({$placeholders})";
                $params = array_merge($params, $value);
            } else {
                $whereParts[] = "{$key} = ?";
                $params[] = $value;
            }
        }

        if (!empty($whereParts)) {
            $sql .= " WHERE " . implode(' AND ', $whereParts);
        }

        if (!empty($this->orderBy)) {
            $orderParts = [];
            foreach ($this->orderBy as $col => $dir) {
                $orderParts[] = "{$col} {$dir}";
            }
            $sql .= " ORDER BY " . implode(', ', $orderParts);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
            if ($this->offset !== null) {
                $sql .= " OFFSET " . $this->offset;
            }
        }

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get first result
     *
     * @return array|null
     */
    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    /**
     * Count results
     *
     * @return int
     */
    public function count(): int
    {
        $options = [];
        if (!empty($this->where)) {
            $options = array_merge($options, $this->where);
        }
        return $this->db->count($this->table, '*', $options);
    }
}

