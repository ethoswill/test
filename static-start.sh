#!/bin/bash

echo "=== STATIC APPROACH ==="

# Create a completely static response
cat > public/index.html << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>App Status</title>
</head>
<body>
    <h1>App is Running</h1>
    <p>Status: OK</p>
    <p>Timestamp: <span id="timestamp"></span></p>
    <script>
        document.getElementById('timestamp').textContent = new Date().toISOString();
    </script>
</body>
</html>
EOF

echo "Starting static file server..."
exec php -S 0.0.0.0:$PORT -t public
