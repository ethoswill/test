<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Purchase Order Confirmation',
                'type' => 'purchase_order',
                'subject' => 'Purchase Order Confirmation - {{po_number}}',
                'body' => "Dear {{client_name}},

Thank you for your order! We have received your purchase order #{{po_number}} and it is currently being processed.

Order Details:
- Purchase Order Number: {{po_number}}
- Total Amount: \${{total_amount}}
- Expected Delivery: {{delivery_date}}
- Vendor: {{vendor_name}}

We will keep you updated on the status of your order. If you have any questions, please don't hesitate to contact us.

Best regards,
{{company_name}}",
                'description' => 'Sent when a new purchase order is created',
                'is_active' => true,
            ],
            [
                'name' => 'Purchase Order Follow Up',
                'type' => 'follow_up',
                'subject' => 'Follow Up on Purchase Order {{po_number}}',
                'body' => "Dear {{client_name}},

We wanted to follow up on your purchase order #{{po_number}} that was placed on {{current_date}}.

Current Status:
- Purchase Order Number: {{po_number}}
- Total Amount: \${{total_amount}}
- Expected Delivery: {{delivery_date}}

Is there anything we can help you with regarding this order? Please let us know if you have any questions or concerns.

Best regards,
{{company_name}}",
                'description' => 'Sent as a follow-up to check on order status',
                'is_active' => true,
            ],
            [
                'name' => 'Purchase Order Reminder',
                'type' => 'reminder',
                'subject' => 'Reminder: Purchase Order {{po_number}} - Action Required',
                'body' => "Dear {{client_name}},

This is a friendly reminder regarding your purchase order #{{po_number}}.

Order Details:
- Purchase Order Number: {{po_number}}
- Total Amount: \${{total_amount}}
- Expected Delivery: {{delivery_date}}

Please review and approve this purchase order at your earliest convenience. If you have any questions or need to make changes, please contact us immediately.

Best regards,
{{company_name}}",
                'description' => 'Sent as a reminder for pending purchase orders',
                'is_active' => true,
            ],
            [
                'name' => 'Purchase Order Delivery Update',
                'type' => 'purchase_order',
                'subject' => 'Delivery Update for Purchase Order {{po_number}}',
                'body' => "Dear {{client_name}},

We have an update regarding your purchase order #{{po_number}}.

Updated Information:
- Purchase Order Number: {{po_number}}
- Total Amount: \${{total_amount}}
- New Delivery Date: {{delivery_date}}
- Vendor: {{vendor_name}}

We apologize for any inconvenience and appreciate your patience. If you have any questions, please don't hesitate to contact us.

Best regards,
{{company_name}}",
                'description' => 'Sent when there are updates to delivery information',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }

        $this->command->info('Email templates seeded successfully!');
    }
}