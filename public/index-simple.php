<?php
// Ultra-simple health check that bypasses Laravel completely
header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'message' => 'Simple PHP app is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server' => 'Railway'
]);
?>
