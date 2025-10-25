#!/bin/bash

echo "=== ULTRA MINIMAL START ==="

# Create the most basic possible index.php
cat > public/index.php << 'EOF'
<?php
header('Content-Type: application/json');
echo '{"status":"ok","message":"Ultra minimal app","timestamp":"' . date('Y-m-d H:i:s') . '"}';
?>
EOF

echo "Starting ultra minimal PHP server..."
exec php -S 0.0.0.0:$PORT -t public
