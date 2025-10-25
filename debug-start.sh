#!/bin/bash

echo "=== RAILWAY DEBUG STARTUP ==="
echo "Timestamp: $(date)"
echo "Port: $PORT"
echo "Working directory: $(pwd)"
echo "PHP version: $(php --version | head -1)"
echo "Available files in public/:"
ls -la public/
echo "=== END DEBUG INFO ==="

echo "Starting PHP server..."
exec php -S 0.0.0.0:$PORT -t public
