<?php
header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'Health check passed',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);
?>
