<?php
header('Content-Type: application/json');

$response = [
    'status' => 'ok',
    'message' => 'Admin endpoint',
    'timestamp' => date('Y-m-d H:i:s'),
    'laravel_status' => 'not_loaded',
    'features' => []
];

try {
    // Try to load Laravel for admin features
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
            $app = require_once __DIR__ . '/../bootstrap/app.php';
            
            $response['laravel_status'] = 'loaded';
            $response['features'] = [
                'framework' => 'Laravel ' . $app->version(),
                'admin_panel' => 'Available at /admin',
                'products' => 'Available at /products',
                'stores' => 'Available at /stores',
                'file_manager' => 'Available at /files'
            ];
            $response['message'] = 'Laravel admin features loaded!';
        }
    }
} catch (Exception $e) {
    $response['laravel_status'] = 'error';
    $response['error'] = $e->getMessage();
    $response['message'] = 'Laravel failed to load, using basic admin';
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
