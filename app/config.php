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

// Load environment variables from .env file
loadEnv(__DIR__ . '/../.env');

// Check for environment variables if not defined from .env (for hosting providers that set env vars)
$envKeys = ['DB_HOST', 'DB_NAME', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT', 'APP_INVENTORY_URL'];
foreach ($envKeys as $key) {
    if (!defined($key) && ($value = getenv($key)) !== false) {
        define($key, $value);
        if (!getenv($key)) {
            putenv($key . '=' . $value);
        }
    }
}
