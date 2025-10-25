<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing vendors or create new ones
        $vendors = [
            [
                'name' => 'Acme Printing Co.',
                'email' => 'orders@acmeprinting.com',
                'phone' => '(555) 123-4567',
                'address' => '123 Print Street, City, State 12345',
                'contact_person' => 'John Smith',
                'is_active' => true,
            ],
            [
                'name' => 'Merchandise Solutions LLC',
                'email' => 'sales@merchsolutions.com',
                'phone' => '(555) 987-6543',
                'address' => '456 Merch Lane, Business City, State 67890',
                'contact_person' => 'Sarah Johnson',
                'is_active' => true,
            ],
            [
                'name' => 'Custom Apparel Inc.',
                'email' => 'orders@customapparel.com',
                'phone' => '(555) 456-7890',
                'address' => '789 Garment Ave, Fashion District, State 54321',
                'contact_person' => 'Mike Davis',
                'is_active' => true,
            ],
        ];

        $createdVendors = [];
        foreach ($vendors as $vendorData) {
            $vendor = Vendor::firstOrCreate(
                ['email' => $vendorData['email']],
                $vendorData
            );
            $createdVendors[] = $vendor;
        }

        // Create sample purchase orders
        $purchaseOrders = [
            [
                'vendor_id' => $createdVendors[0]->id,
                'po_number' => 'PO2024100001',
                'client_name' => 'Tech Startup Inc.',
                'client_email' => 'procurement@techstartup.com',
                'client_phone' => '(555) 111-2222',
                'client_address' => '100 Innovation Drive, Tech City, State 11111',
                'contact_person' => 'Jane Wilson',
                'description' => 'Custom branded merchandise for Q4 company event',
                'line_items' => [
                    [
                        'description' => 'Custom Logo T-Shirts (Black)',
                        'quantity' => 50,
                        'unit_price' => 12.50,
                        'total' => 625.00,
                    ],
                    [
                        'description' => 'Custom Logo T-Shirts (White)',
                        'quantity' => 50,
                        'unit_price' => 12.50,
                        'total' => 625.00,
                    ],
                    [
                        'description' => 'Custom Logo Hoodies',
                        'quantity' => 25,
                        'unit_price' => 35.00,
                        'total' => 875.00,
                    ],
                    [
                        'description' => 'Custom Logo Tote Bags',
                        'quantity' => 100,
                        'unit_price' => 8.75,
                        'total' => 875.00,
                    ],
                ],
                'subtotal' => 3000.00,
                'tax_rate' => 8.5,
                'tax_amount' => 255.00,
                'total_amount' => 3255.00,
                'po_date' => now()->subDays(5),
                'delivery_date' => now()->addDays(10),
                'status' => 'sent',
                'notes' => 'Please ensure all items have our company logo prominently displayed. Delivery to main office address.',
                'terms_conditions' => 'Payment due within 30 days of delivery. Rush order fee may apply for expedited delivery.',
                'delivery_address' => '100 Innovation Drive, Tech City, State 11111',
            ],
            [
                'vendor_id' => $createdVendors[1]->id,
                'po_number' => 'PO2024100002',
                'client_name' => 'Fitness Center Chain',
                'client_email' => 'orders@fitnesscenter.com',
                'client_phone' => '(555) 333-4444',
                'client_address' => '500 Gym Street, Fitness City, State 22222',
                'contact_person' => 'Robert Martinez',
                'description' => 'Staff uniforms and promotional materials for new location opening',
                'line_items' => [
                    [
                        'description' => 'Staff Polo Shirts (Navy)',
                        'quantity' => 30,
                        'unit_price' => 18.00,
                        'total' => 540.00,
                    ],
                    [
                        'description' => 'Staff Polo Shirts (White)',
                        'quantity' => 30,
                        'unit_price' => 18.00,
                        'total' => 540.00,
                    ],
                    [
                        'description' => 'Gym Towels (Custom Logo)',
                        'quantity' => 200,
                        'unit_price' => 6.25,
                        'total' => 1250.00,
                    ],
                    [
                        'description' => 'Water Bottles (Custom Logo)',
                        'quantity' => 150,
                        'unit_price' => 4.50,
                        'total' => 675.00,
                    ],
                    [
                        'description' => 'Banner (3x6 ft)',
                        'quantity' => 2,
                        'unit_price' => 85.00,
                        'total' => 170.00,
                    ],
                ],
                'subtotal' => 3175.00,
                'tax_rate' => 7.5,
                'tax_amount' => 238.13,
                'total_amount' => 3413.13,
                'po_date' => now()->subDays(3),
                'delivery_date' => now()->addDays(7),
                'status' => 'approved',
                'notes' => 'Rush order needed for grand opening event. Please prioritize this order.',
                'terms_conditions' => 'Payment due within 15 days of delivery. Quality inspection required before final payment.',
                'delivery_address' => '500 Gym Street, Fitness City, State 22222',
            ],
            [
                'vendor_id' => $createdVendors[2]->id,
                'po_number' => 'PO2024100003',
                'client_name' => 'Restaurant Group',
                'client_email' => 'manager@restaurantgroup.com',
                'client_phone' => '(555) 555-6666',
                'client_address' => '300 Food Court, Restaurant Row, State 33333',
                'contact_person' => 'Lisa Chen',
                'description' => 'Employee uniforms and kitchen supplies for 5 restaurant locations',
                'line_items' => [
                    [
                        'description' => 'Chef Aprons (White)',
                        'quantity' => 25,
                        'unit_price' => 22.00,
                        'total' => 550.00,
                    ],
                    [
                        'description' => 'Server Shirts (Black)',
                        'quantity' => 40,
                        'unit_price' => 15.75,
                        'total' => 630.00,
                    ],
                    [
                        'description' => 'Kitchen Hats',
                        'quantity' => 25,
                        'unit_price' => 8.50,
                        'total' => 212.50,
                    ],
                    [
                        'description' => 'Name Tags (Custom)',
                        'quantity' => 50,
                        'unit_price' => 3.25,
                        'total' => 162.50,
                    ],
                    [
                        'description' => 'Menu Covers (Leather)',
                        'quantity' => 20,
                        'unit_price' => 45.00,
                        'total' => 900.00,
                    ],
                ],
                'subtotal' => 2455.00,
                'tax_rate' => 6.0,
                'tax_amount' => 147.30,
                'total_amount' => 2602.30,
                'po_date' => now()->subDays(1),
                'delivery_date' => now()->addDays(14),
                'status' => 'draft',
                'notes' => 'Please coordinate delivery with each location manager. Contact information provided separately.',
                'terms_conditions' => 'Payment due within 45 days of delivery. Bulk discount applied.',
                'delivery_address' => '300 Food Court, Restaurant Row, State 33333',
            ],
        ];

        foreach ($purchaseOrders as $poData) {
            PurchaseOrder::create($poData);
        }

        $this->command->info('Sample vendors and purchase orders created successfully!');
    }
}
