<?php
// Hybrid approach: Simple PHP with some Laravel features
header('Content-Type: application/json');

// Try to load Laravel if possible, but don't fail if it doesn't work
$laravel_loaded = false;
$admin_features = [];

try {
    // Try to load Laravel
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
            $app = require_once __DIR__ . '/../bootstrap/app.php';
            $laravel_loaded = true;
            
            // Add some basic Laravel features
            $admin_features = [
                'framework' => 'Laravel ' . $app->version(),
                'status' => 'loaded',
                'admin_url' => '/admin'
            ];
        }
    }
} catch (Exception $e) {
    // Laravel failed to load, continue with simple PHP
    $laravel_loaded = false;
}

// Build response
$response = [
    'status' => 'ok',
    'message' => $laravel_loaded ? 'Hybrid PHP + Laravel app is running' : 'Simple PHP app is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server' => 'Railway',
    'laravel_loaded' => $laravel_loaded
];

if ($laravel_loaded) {
    $response['admin_features'] = $admin_features;
    $response['next_steps'] = 'Laravel is loaded - ready for full admin panel';
} else {
    $response['next_steps'] = 'Laravel not loaded - using simple PHP fallback';
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
