<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

// Check if the application is running on localhost
if ($_SERVER['HTTP_HOST'] === 'localhost' || str_starts_with($_SERVER['HTTP_HOST'], '127.0.0.1')) {
    $domain = 'localhost';
} else {
    // Use preg_replace for live domains
    $domain = preg_replace('#^https?://#', '', getenv('URL_PATH'));
}

session_set_cookie_params([
    'lifetime' => 3600,
    'domain' => $domain,
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

if (!isset($_SESSION['last_regeneration'])) {

	session_regenerate_id(true);
	$_SESSION['last_regeneration'] = time();

}else{

	$interval = 60 * 30;

	if (time() - $_SESSION['last_regeneration'] >= $interval) {
		
		session_regenerate_id(true);
		$_SESSION['last_regeneration'] = time();
	}
}

require_once(__DIR__ . '/../core/App.php');
require_once(__DIR__ . '/../core/Middleware.php');
require_once(__DIR__ . '/../core/Controller.php');
require_once(__DIR__ . '/../core/Model.php');
require_once(__DIR__ . '/../core/View.php');
require_once(__DIR__ . '/../core/Database.php');