<?php
/**
 * Load environment variables from .env file
 */
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) {
            return;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Split on first equals sign
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                
                // Remove quotes if present
                $value = trim($value, '"\'' );
                
                // Set as constant if not already defined
                if (!defined($key)) {
                    define($key, $value);
                }
                
                // Also set as environment variable for getenv() to work
                if (!getenv($key)) {
                    putenv($key . '=' . $value);
                }
            }
        }
    }
}

// Load environment variables
loadEnv(__DIR__ . '/../.env');

/**
 * Define Database credentials
 */
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost'); // Default fallback
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'luna'); // Default fallback
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root'); // Default fallback
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', 'rootroot'); // Default fallback
}

if (!defined('DB_PORT')) {
    define('DB_PORT', '3306'); // Default fallback
}

// Application URLs
if (!defined('APP_INVENTORY_URL')) {
    define('APP_INVENTORY_URL', getenv('APP_INVENTORY_URL') ?: 'http://localhost/inventory'); // Default fallback
}
