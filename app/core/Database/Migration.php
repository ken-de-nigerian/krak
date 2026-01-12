<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Database;

use Medoo\Medoo;

/**
 * Database Migration System
 * Implements SRP and OCP principles
 */
abstract class Migration
{
    /**
     * @var Medoo
     */
    protected Medoo $db;

    /**
     * @param Medoo $db
     */
    public function __construct(Medoo $db)
    {
        $this->db = $db;
    }

    /**
     * Run the migration
     *
     * @return void
     */
    abstract public function up(): void;

    /**
     * Reverse the migration
     *
     * @return void
     */
    abstract public function down(): void;

    /**
     * Get migration name
     *
     * @return string
     */
    public function getName(): string
    {
        $reflection = new \ReflectionClass($this);
        return $reflection->getShortName();
    }
}

