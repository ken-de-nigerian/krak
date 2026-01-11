<?php

const FIR = true;

// Database Settings
define('DB_TYPE', getenv('DB_TYPE'));
define('DB_HOST', getenv('DB_HOST'));
define('DB_USERNAME', getenv('DB_USERNAME'));
define('DB_DATABASE', getenv('DB_DATABASE'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));

// External Paths
define('URL_PATH', getenv('URL_PATH'));

// Internal Paths
const PUBLIC_PATH = 'public';
const THEME_PATH = 'theme';
const UPLOADS_PATH = 'uploads';

// Miscellaneous
const COOKIE_PATH = '/';

// Config Variables
$GLOBALS['config'] = array(
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 3600
	),
	'session' => array(
		'session_admin' => 'waveAdmin',
		'session_name' => 'waveUser'
	)
);