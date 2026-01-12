<?php

/**
 * A lightweight PHP MVC Framework.
 *
 */

// Include Composer autoload file
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file
try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    // Handle the case where .env file is not found
    // This could be empty if .env is not required
}

// Include custom configuration files
require_once(__DIR__ . '/../app/includes/config.php');
require_once(__DIR__ . '/../app/includes/init.php');
require_once(__DIR__ . '/../app/includes/info.php');

// Initialize the application
$app = new KenDeNigerian\Krak\Core\App();
