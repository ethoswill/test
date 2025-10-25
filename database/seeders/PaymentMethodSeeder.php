<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some customers to add payment methods to
        $customers = Customer::take(5)->get();
        
        if ($customers->isEmpty()) {
            $this->command->info('No customers found. Please run CustomerSeeder first.');
            return;
        }

        $paymentMethods = [
            [
                'type' => 'card',
                'provider' => 'stripe',
                'provider_id' => 'pm_' . \Str::random(24),
                'card_brand' => 'visa',
                'card_last_four' => '4242',
                'card_exp_month' => '12',
                'card_exp_year' => '2025',
                'card_holder_name' => 'John Doe',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'type' => 'card',
                'provider' => 'stripe',
                'provider_id' => 'pm_' . \Str::random(24),
                'card_brand' => 'mastercard',
                'card_last_four' => '5555',
                'card_exp_month' => '08',
                'card_exp_year' => '2026',
                'card_holder_name' => 'Jane Smith',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'type' => 'card',
                'provider' => 'stripe',
                'provider_id' => 'pm_' . \Str::random(24),
                'card_brand' => 'amex',
                'card_last_four' => '1234',
                'card_exp_month' => '03',
                'card_exp_year' => '2024',
                'card_holder_name' => 'Bob Johnson',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'type' => 'paypal',
                'provider' => 'paypal',
                'provider_id' => 'pp_' . \Str::random(20),
                'card_brand' => null,
                'card_last_four' => null,
                'card_exp_month' => null,
                'card_exp_year' => null,
                'card_holder_name' => 'Alice Brown',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'type' => 'card',
                'provider' => 'stripe',
                'provider_id' => 'pm_' . \Str::random(24),
                'card_brand' => 'discover',
                'card_last_four' => '9999',
                'card_exp_month' => '11',
                'card_exp_year' => '2027',
                'card_holder_name' => 'Charlie Wilson',
                'is_default' => true,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $index => $customer) {
            if (isset($paymentMethods[$index])) {
                $paymentMethodData = $paymentMethods[$index];
                $paymentMethodData['customer_id'] = $customer->id;
                
                PaymentMethod::create($paymentMethodData);
                
                $this->command->info("Added payment method for customer: {$customer->email}");
            }
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}