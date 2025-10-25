<?php
header('Content-Type: application/json');

// Ultra-minimal base that always works
$response = [
    'status' => 'ok',
    'message' => 'Ethos Merch Admin App is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'app_name' => 'Ethos Merch Admin',
    'version' => '1.0.0',
    'endpoints' => [
        'main' => '/',
        'admin' => '/admin.php',
        'dashboard' => '/dashboard.php',
        'products' => '/products.php',
        'stores' => '/stores.php',
        'files' => '/files.php',
        'health' => '/health.php'
    ],
    'features' => [
        'Filament Admin Panel' => 'Available via endpoints',
        'Product Management' => '/products.php',
        'Store Management' => '/stores.php',
        'File Manager' => '/files.php',
        'Dashboard' => '/dashboard.php'
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>