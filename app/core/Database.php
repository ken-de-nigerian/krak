<?php

namespace Fir\Connection;

use Medoo\Medoo;
use PDOException;

/**
 * The database class which creates the database connection
 */
class Database
{
    /**
     * Starts the database connection
     * @return Medoo|void
     */
    public function connect()
    {
        try {
            return new Medoo([
                'database_type' => DB_TYPE,
                'server' => DB_HOST,
                'database_name' => DB_DATABASE,
                'username' => DB_USERNAME,
                'password' => DB_PASSWORD
            ]);
        } catch (PDOException) {
            // Custom database connection error message
            $errorMessage = "An error occurred while connecting to the database. Please try again later.";
            // You can log the actual error for debugging purposes
            // error_log($e->getMessage(), 0);
            die($errorMessage);
        }
    }
}