<?php

namespace Database\Seeders;

use App\Models\SockOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SockOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sockOrders = [
            [
                'eid' => 'EID00000000000001',
                'order_number' => 'ETH1001',
                'customer_name' => 'Will Hunt',
                'order_date' => '2025-10-21',
                'status' => 'Submitted',
                'quantity' => 100,
                'notes' => 'Custom logo embroidery required',
            ],
            [
                'eid' => 'EID00000000000002',
                'order_number' => 'ETH1001',
                'customer_name' => 'Will Hunt',
                'order_date' => '2025-10-21',
                'status' => 'Sample In Production',
                'quantity' => 100,
                'notes' => 'Sample being prepared for approval',
            ],
            [
                'eid' => 'EID00000000000003',
                'order_number' => 'ETH1001',
                'customer_name' => 'Will Hunt',
                'order_date' => '2025-10-21',
                'status' => 'In Bulk Production',
                'quantity' => 100,
                'notes' => 'Bulk production in progress',
            ],
            [
                'eid' => 'EID00000000000004',
                'order_number' => 'ETH1001',
                'customer_name' => 'Will Hunt',
                'order_date' => '2025-10-21',
                'status' => 'Shipped',
                'quantity' => 100,
                'notes' => 'Order shipped to customer',
            ],
            [
                'eid' => 'EID00000000000005',
                'order_number' => 'ETH1001',
                'customer_name' => 'Will Hunt',
                'order_date' => '2025-10-21',
                'status' => 'Received',
                'quantity' => 100,
                'notes' => 'Order received by customer',
            ],
            // Additional sample data for different orders
            [
                'eid' => 'EID00000000000006',
                'order_number' => 'ETH1002',
                'customer_name' => 'Sarah Johnson',
                'order_date' => '2025-10-22',
                'status' => 'Submitted',
                'quantity' => 250,
                'notes' => 'Corporate order with custom colors',
            ],
            [
                'eid' => 'EID00000000000007',
                'order_number' => 'ETH1002',
                'customer_name' => 'Sarah Johnson',
                'order_date' => '2025-10-22',
                'status' => 'Sample In Production',
                'quantity' => 250,
                'notes' => 'Sample with custom color scheme',
            ],
            [
                'eid' => 'EID00000000000008',
                'order_number' => 'ETH1003',
                'customer_name' => 'Mike Chen',
                'order_date' => '2025-10-23',
                'status' => 'In Bulk Production',
                'quantity' => 500,
                'notes' => 'Large bulk order for retail store',
            ],
        ];

        foreach ($sockOrders as $order) {
            SockOrder::create($order);
        }
    }
}
