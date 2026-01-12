<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\Database;

use Medoo\Medoo;

/**
 * Database Migrator
 * Handles running migrations
 */
class Migrator
{
    /**
     * @var Medoo
     */
    private Medoo $db;

    /**
     * @var string
     */
    private string $migrationsPath;

    /**
     * @var string
     */
    private string $migrationsTable = 'migrations';

    /**
     * @param Medoo $db
     * @param string $migrationsPath
     */
    public function __construct(Medoo $db, string $migrationsPath = null)
    {
        $this->db = $db;
        $this->migrationsPath = $migrationsPath ?? __DIR__ . '/../../../database/migrations';
        
        $this->ensureMigrationsTable();
    }

    /**
     * Ensure migrations table exists
     *
     * @return void
     */
    private function ensureMigrationsTable(): void
    {
        if (!$this->db->has($this->migrationsTable)) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->migrationsTable}` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `migration` VARCHAR(255) NOT NULL,
                `batch` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
        }
    }

    /**
     * Run pending migrations
     *
     * @return void
     */
    public function migrate(): void
    {
        $ranMigrations = $this->getRanMigrations();
        $migrations = $this->getMigrationFiles();
        
        $batch = $this->getNextBatchNumber();
        
        foreach ($migrations as $migration) {
            $migrationName = $this->getMigrationName($migration);
            
            if (!in_array($migrationName, $ranMigrations)) {
                $this->runMigration($migration, $batch);
            }
        }
    }

    /**
     * Rollback last batch of migrations
     *
     * @return void
     */
    public function rollback(): void
    {
        $lastBatch = $this->getLastBatch();
        
        if ($lastBatch === null) {
            return;
        }
        
        $migrations = $this->getMigrationsByBatch($lastBatch);
        
        foreach (array_reverse($migrations) as $migration) {
            $this->rollbackMigration($migration['migration']);
        }
    }

    /**
     * Get migration files
     *
     * @return array
     */
    private function getMigrationFiles(): array
    {
        if (!is_dir($this->migrationsPath)) {
            return [];
        }
        
        $files = glob($this->migrationsPath . '/*.php');
        sort($files);
        
        return $files;
    }

    /**
     * Get migration name from file
     *
     * @param string $file
     * @return string
     */
    private function getMigrationName(string $file): string
    {
        return basename($file, '.php');
    }

    /**
     * Run a migration
     *
     * @param string $file
     * @param int $batch
     * @return void
     */
    private function runMigration(string $file, int $batch): void
    {
        require_once $file;
        
        $className = $this->getMigrationName($file);
        $className = str_replace('_', '', ucwords($className, '_'));
        
        if (!class_exists($className)) {
            throw new \Exception("Migration class {$className} not found");
        }
        
        $migration = new $className($this->db);
        $migration->up();
        
        $this->db->insert($this->migrationsTable, [
            'migration' => $this->getMigrationName($file),
            'batch' => $batch
        ]);
    }

    /**
     * Rollback a migration
     *
     * @param string $migrationName
     * @return void
     */
    private function rollbackMigration(string $migrationName): void
    {
        $file = $this->migrationsPath . '/' . $migrationName . '.php';
        
        if (!file_exists($file)) {
            return;
        }
        
        require_once $file;
        
        $className = str_replace('_', '', ucwords($migrationName, '_'));
        
        if (!class_exists($className)) {
            throw new \Exception("Migration class {$className} not found");
        }
        
        $migration = new $className($this->db);
        $migration->down();
        
        $this->db->delete($this->migrationsTable, [
            'migration' => $migrationName
        ]);
    }

    /**
     * Get ran migrations
     *
     * @return array
     */
    private function getRanMigrations(): array
    {
        $migrations = $this->db->select($this->migrationsTable, 'migration');
        return array_column($migrations, 'migration');
    }

    /**
     * Get next batch number
     *
     * @return int
     */
    private function getNextBatchNumber(): int
    {
        $lastBatch = $this->db->max($this->migrationsTable, 'batch');
        return ($lastBatch ?? 0) + 1;
    }

    /**
     * Get last batch number
     *
     * @return int|null
     */
    private function getLastBatch(): ?int
    {
        return $this->db->max($this->migrationsTable, 'batch');
    }

    /**
     * Get migrations by batch
     *
     * @param int $batch
     * @return array
     */
    private function getMigrationsByBatch(int $batch): array
    {
        return $this->db->select($this->migrationsTable, '*', [
            'batch' => $batch
        ]);
    }
}

