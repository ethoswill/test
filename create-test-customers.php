<?php

/**
 * Script to create test customers in Medusa database
 */

echo "🔧 Creating Test Customers in Medusa\n";
echo "====================================\n\n";

// Test customers data
$testCustomers = [
    [
        'id' => 'cus_001',
        'email' => 'john.doe@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '+1-555-0123',
        'has_account' => true,
        'metadata' => json_encode([
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'last_login_at' => '2025-10-21 10:30:00',
            'preferred_language' => 'en',
            'marketing_consent' => true,
            'newsletter_subscribed' => true
        ]),
        'created_at' => '2025-10-21 10:00:00',
        'updated_at' => '2025-10-21 10:30:00',
    ],
    [
        'id' => 'cus_002',
        'email' => 'jane.smith@example.com',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'phone' => '+1-555-0456',
        'has_account' => true,
        'metadata' => json_encode([
            'date_of_birth' => '1985-12-03',
            'gender' => 'female',
            'last_login_at' => '2025-10-20 15:45:00',
            'preferred_language' => 'en',
            'marketing_consent' => false,
            'newsletter_subscribed' => false,
            'vip_status' => 'gold'
        ]),
        'created_at' => '2025-10-20 15:00:00',
        'updated_at' => '2025-10-20 15:45:00',
    ],
    [
        'id' => 'cus_003',
        'email' => 'bob.wilson@example.com',
        'first_name' => 'Bob',
        'last_name' => 'Wilson',
        'phone' => '+1-555-0789',
        'has_account' => false,
        'metadata' => json_encode([
            'date_of_birth' => '1992-08-22',
            'gender' => 'male',
            'last_login_at' => null,
            'preferred_language' => 'en',
            'marketing_consent' => true,
            'newsletter_subscribed' => true,
            'guest_checkout' => true
        ]),
        'created_at' => '2025-10-21 08:00:00',
        'updated_at' => '2025-10-21 08:00:00',
    ],
    [
        'id' => 'cus_004',
        'email' => 'alice.brown@example.com',
        'first_name' => 'Alice',
        'last_name' => 'Brown',
        'phone' => '+1-555-0321',
        'has_account' => true,
        'metadata' => json_encode([
            'date_of_birth' => '1988-03-10',
            'gender' => 'female',
            'last_login_at' => '2025-10-19 14:20:00',
            'preferred_language' => 'es',
            'marketing_consent' => true,
            'newsletter_subscribed' => true,
            'vip_status' => 'platinum',
            'referral_source' => 'social_media'
        ]),
        'created_at' => '2025-10-19 14:00:00',
        'updated_at' => '2025-10-19 14:20:00',
    ],
    [
        'id' => 'cus_005',
        'email' => 'charlie.davis@example.com',
        'first_name' => 'Charlie',
        'last_name' => 'Davis',
        'phone' => '+1-555-0654',
        'has_account' => true,
        'metadata' => json_encode([
            'date_of_birth' => '1995-11-28',
            'gender' => 'male',
            'last_login_at' => '2025-10-21 09:15:00',
            'preferred_language' => 'en',
            'marketing_consent' => false,
            'newsletter_subscribed' => false,
            'company_name' => 'Tech Corp',
            'b2b_customer' => true
        ]),
        'created_at' => '2025-10-21 09:00:00',
        'updated_at' => '2025-10-21 09:15:00',
    ]
];

try {
    // Connect to Medusa database
    $pdo = new PDO(
        'pgsql:host=127.0.0.1;port=5432;dbname=medusa-ethos-test',
        'my_app_user',
        'my_secure_password'
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Insert test customers
    $inserted = 0;
    foreach ($testCustomers as $customer) {
        try {
            $sql = "INSERT INTO customer (
                id, email, first_name, last_name, phone, has_account, metadata, created_at, updated_at
            ) VALUES (
                :id, :email, :first_name, :last_name, :phone, :has_account, :metadata, :created_at, :updated_at
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($customer);
            $inserted++;
            
            echo "✅ Created customer: {$customer['email']} ({$customer['first_name']} {$customer['last_name']})\n";
        } catch (PDOException $e) {
            if ($e->getCode() == 23505) { // Unique constraint violation
                echo "⚠️  Customer already exists: {$customer['email']}\n";
            } else {
                echo "❌ Error creating customer {$customer['email']}: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n🎉 Test customers created successfully!\n";
    echo "📊 Total customers created: {$inserted}\n\n";
    
    echo "🎯 Next steps:\n";
    echo "1. Go to: http://localhost:8000/admin/customers\n";
    echo "2. Click 'Sync Customers from Medusa'\n";
    echo "3. Watch the customers appear in your Filament admin!\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Make sure Medusa is running and the database is accessible.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
