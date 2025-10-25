<?php

/**
 * Script to help get the correct Medusa API key
 * The current key appears to be a secret key, but we need a publishable key
 */

echo "🔑 Getting Correct Medusa API Key\n";
echo "=================================\n\n";

echo "⚠️  Issue Found:\n";
echo "The API key you provided starts with 'sk_' (secret key)\n";
echo "But Medusa store API requires a 'pk_' (publishable key)\n\n";

echo "📋 To get the correct API key:\n";
echo "=============================\n\n";

echo "1. Go to Medusa admin: http://localhost:9000/app\n";
echo "2. Login with: admin@example.com / password123\n";
echo "3. Go to Settings → API Key Management\n";
echo "4. Look for a 'Publishable' API key (starts with 'pk_')\n";
echo "5. If you don't see one, create a new 'Publishable' key\n\n";

echo "🔍 Alternative: Check if you have a publishable key:\n";
echo "Look for any key that starts with 'pk_' in your Medusa admin\n\n";

echo "📝 Once you have the publishable key:\n";
echo "Run: php update-env-with-publishable-key.php\n\n";

echo "🎯 What we need:\n";
echo "- Publishable key (pk_...) for store API\n";
echo "- Secret key (sk_...) for admin API\n";
echo "- We're using the store API for sync\n\n";

// Open Medusa admin
exec("open 'http://localhost:9000/app'");
echo "🌐 Opening Medusa admin interface...\n";





