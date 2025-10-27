<?php

namespace Database\Seeders;

use App\Models\Sock;
use Illuminate\Database\Seeder;

class SockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socks = [
            [
                'name' => 'Athletic Crew Socks',
                'description' => 'High-performance athletic socks designed for sports and active lifestyle. Features moisture-wicking technology and cushioned sole for comfort during intense activities.',
                'color' => 'Black, White, Navy, Gray, Athletic Colors',
                'size' => 'S, M, L, XL',
                'material' => 'cotton',
                'price' => 12.99,
                'is_active' => true,
            ],
            [
                'name' => 'Dress Socks',
                'description' => 'Professional dress socks perfect for business attire and formal occasions. Thin profile with reinforced heel and toe for durability.',
                'color' => 'Black, Navy, Charcoal, Brown',
                'size' => 'S, M, L, XL',
                'material' => 'cotton',
                'price' => 15.99,
                'is_active' => true,
            ],
            [
                'name' => 'Merino Wool Hiking Socks',
                'description' => 'Premium merino wool socks ideal for hiking and outdoor adventures. Natural temperature regulation and odor resistance for extended wear.',
                'color' => 'Natural, Dark Gray, Olive',
                'size' => 'S, M, L, XL',
                'material' => 'merino',
                'price' => 24.99,
                'is_active' => true,
            ],
            [
                'name' => 'Bamboo Fiber Socks',
                'description' => 'Eco-friendly bamboo fiber socks with antibacterial properties. Soft, breathable, and perfect for everyday wear with environmental consciousness.',
                'color' => 'White, Black, Natural',
                'size' => 'S, M, L, XL',
                'material' => 'bamboo',
                'price' => 18.99,
                'is_active' => true,
            ],
            [
                'name' => 'Compression Socks',
                'description' => 'Medical-grade compression socks designed to improve circulation and reduce leg fatigue. Ideal for travel, standing work, or recovery.',
                'color' => 'Black, Navy, Beige',
                'size' => 'S, M, L, XL',
                'material' => 'synthetic',
                'price' => 29.99,
                'is_active' => true,
            ],
            [
                'name' => 'No-Show Socks',
                'description' => 'Low-cut no-show socks perfect for sneakers and casual shoes. Stay hidden while providing comfort and preventing blisters.',
                'color' => 'Black, White, Gray, Navy',
                'size' => 'S, M, L, XL',
                'material' => 'cotton',
                'price' => 9.99,
                'is_active' => true,
            ],
            [
                'name' => 'Winter Thermal Socks',
                'description' => 'Heavy-duty thermal socks for cold weather. Extra insulation and moisture-wicking properties to keep feet warm and dry.',
                'color' => 'Black, Gray, Brown, Navy',
                'size' => 'S, M, L, XL',
                'material' => 'wool',
                'price' => 19.99,
                'is_active' => true,
            ],
            [
                'name' => 'Running Socks',
                'description' => 'Specialized running socks with targeted cushioning and ventilation zones. Designed to reduce friction and enhance performance.',
                'color' => 'White, Black, Bright Colors',
                'size' => 'S, M, L, XL',
                'material' => 'synthetic',
                'price' => 16.99,
                'is_active' => true,
            ],
        ];

        foreach ($socks as $sock) {
            Sock::create($sock);
        }
    }
}