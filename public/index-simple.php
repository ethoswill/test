<?php
// Ultra-minimal healthcheck endpoint
header('Content-Type: application/json');
http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'message' => 'Server is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);
exit;
