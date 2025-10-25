<?php

/**
 * Script to add rewards data to existing customers in Medusa
 */

echo "🎁 Adding Rewards Data to Customers\n";
echo "==================================\n\n";

try {
    // Connect to Medusa database
    $pdo = new PDO(
        'pgsql:host=127.0.0.1;port=5432;dbname=medusa-ethos-test',
        'my_app_user',
        'my_secure_password'
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all customers
    $stmt = $pdo->query("SELECT id, email, first_name, last_name, metadata FROM customer");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $updated = 0;
    foreach ($customers as $customer) {
        // Parse existing metadata
        $metadata = json_decode($customer['metadata'] ?? '{}', true);
        
        // Generate rewards data
        $rewardsData = [
            'total_points' => rand(500, 5000),
            'available_points' => rand(100, 2000),
            'lifetime_points' => rand(1000, 10000),
            'tier' => ['Bronze', 'Silver', 'Gold', 'Platinum'][rand(0, 3)],
            'join_date' => date('Y-m-d', strtotime('-' . rand(30, 365) . ' days')),
            'last_activity' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
            'referral_code' => strtoupper(substr($customer['first_name'], 0, 2) . substr($customer['last_name'], 0, 2) . rand(100, 999)),
            'total_orders' => rand(1, 50),
            'total_spent' => rand(100, 5000),
            'favorite_categories' => ['Apparel', 'Accessories', 'Home', 'Electronics'][rand(0, 3)],
            'birthday_month' => rand(1, 12),
            'newsletter_subscribed' => rand(0, 1) === 1,
            'sms_notifications' => rand(0, 1) === 1,
            'rewards_earned_this_month' => rand(50, 500),
            'rewards_redeemed_this_month' => rand(0, 200),
            'next_tier_progress' => rand(0, 100),
            'special_offers_available' => rand(0, 3),
            'loyalty_multiplier' => rand(1, 3),
            'anniversary_date' => date('Y-m-d', strtotime('-' . rand(30, 1095) . ' days')),
            'preferred_reward_type' => ['discount', 'free_shipping', 'exclusive_access', 'cash_back'][rand(0, 3)]
        ];
        
        // Merge with existing metadata
        $metadata['rewards'] = $rewardsData;
        
        // Update customer with rewards data
        $updateStmt = $pdo->prepare("
            UPDATE customer 
            SET metadata = :metadata, updated_at = NOW()
            WHERE id = :id
        ");
        
        $updateStmt->execute([
            'metadata' => json_encode($metadata),
            'id' => $customer['id']
        ]);
        
        $updated++;
        echo "✅ Added rewards data to: {$customer['email']} ({$customer['first_name']} {$customer['last_name']})\n";
    }
    
    echo "\n🎉 Rewards data added successfully!\n";
    echo "📊 Total customers updated: {$updated}\n\n";
    
    echo "🎯 Next steps:\n";
    echo "1. Go to: http://localhost:9000/app/customers\n";
    echo "2. Click on any customer to view their profile\n";
    echo "3. Scroll down to see the new 'Customer Rewards' widget!\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Make sure Medusa is running and the database is accessible.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}





