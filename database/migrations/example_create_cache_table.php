<?php

namespace Database\Migrations;

use Fir\Core\Database\Migration;
use Medoo\Medoo;

/**
 * Example Migration
 * This is a template for creating migrations
 */
class ExampleCreateCacheTable extends Migration
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up(): void
    {
        // Example: Create a cache table
        // $this->db->query("CREATE TABLE IF NOT EXISTS `cache` (
        //     `key` VARCHAR(255) PRIMARY KEY,
        //     `value` TEXT,
        //     `expires_at` TIMESTAMP,
        //     `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // )");
    }

    /**
     * Reverse the migration
     *
     * @return void
     */
    public function down(): void
    {
        // Example: Drop the cache table
        // $this->db->query("DROP TABLE IF EXISTS `cache`");
    }
}

