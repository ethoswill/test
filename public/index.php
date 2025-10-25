<?php
header('Content-Type: application/json');

// Ultra-minimal base that always works
$response = [
    'status' => 'ok',
    'message' => 'App is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'endpoints' => [
        'main' => '/',
        'admin' => '/admin.php',
        'health' => '/health.php'
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>