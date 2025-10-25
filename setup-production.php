<?php

/**
 * Production Setup Script
 * Run this after deployment to set up the application
 */

echo "🚀 Setting up production environment...\n";

// Check if we're in production
if (app()->environment('production')) {
    echo "✅ Production environment detected\n";
} else {
    echo "⚠️  Not in production environment\n";
}

// Generate application key if not set
if (empty(config('app.key'))) {
    echo "🔑 Generating application key...\n";
    Artisan::call('key:generate');
    echo "✅ Application key generated\n";
}

// Run migrations
echo "🗄️ Running database migrations...\n";
try {
    Artisan::call('migrate', ['--force' => true]);
    echo "✅ Database migrations completed\n";
} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}

// Create storage link
echo "🔗 Creating storage link...\n";
try {
    Artisan::call('storage:link');
    echo "✅ Storage link created\n";
} catch (Exception $e) {
    echo "❌ Storage link failed: " . $e->getMessage() . "\n";
}

// Clear caches
echo "🧹 Clearing caches...\n";
Artisan::call('config:cache');
Artisan::call('route:cache');
Artisan::call('view:cache');
echo "✅ Caches cleared\n";

// Create admin user if none exists
echo "👤 Checking for admin user...\n";
$userCount = \App\Models\User::count();
if ($userCount === 0) {
    echo "⚠️  No users found. Please create an admin user:\n";
    echo "   Run: php artisan make:filament-user\n";
} else {
    echo "✅ Users found: $userCount\n";
}

echo "\n🎉 Production setup completed!\n";
echo "📱 Your app should now be accessible at: " . config('app.url') . "\n";
echo "🔐 Admin panel: " . config('app.url') . "/admin\n";
