<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnhancedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create "The Staple Tee - White" product similar to the image
        $stapleTee = Product::create([
            'name' => 'The Staple Tee - White',
            'sku' => 'STAPLE-TEE-WHITE',
            'description' => 'Classic white t-shirt made from 100% cotton. Perfect for everyday wear and customization.',
            'price' => 13.00,
            'cost' => 8.00,
            'stock_quantity' => 0, // Will be calculated from variants
            'min_stock_level' => 10,
            'category' => 'Clothing',
            'brand' => 'ASC',
            'status' => 'supplier_product',
            'images' => ['products/staple-tee-white-front.jpg', 'products/staple-tee-white-back.jpg'],
            'specifications' => [
                'Material' => '100% Cotton',
                'Weight' => '4-6oz',
                'Fit' => 'Regular',
                'Care' => 'Machine wash cold, tumble dry low',
            ],
            'weight' => 0.2,
            'dimensions' => 'Standard t-shirt dimensions',
            'barcode' => '1234567890132',
            'is_featured' => false,
            'published_at' => now(),
            // E-commerce fields
            'website_url' => 'https://ascolour.com/staple-tee-5001/',
            'hs_code' => '600.80.8139',
            'parent_product' => 'The Mens Tee',
            'supplier' => 'ASC',
            'product_type' => 'Mens Tee',
            'fabric' => '100% Cotton',
            'care_instructions' => 'Wash with similar colors. Do not tumble dry.',
            'lead_times' => 'Up to 4 weeks including shipping',
            'available_sizes' => 'XS - 3XL',
            'customization_methods' => ['IHP', 'EMB', 'PATCH'],
            'model_size' => 'XS - 3XL',
            'starting_from_price' => 13.00,
            'minimums' => 'No minimums',
            'last_inventory_sync' => now()->subDays(5),
            'has_variants' => true,
        ]);

        // Create variants for the Staple Tee
        $variants = [
            ['variant_name' => 'XSmall', 'sku' => '5001-WHITE-F-XS', 'color' => 'White', 'size' => 'XS', 'weight' => 4, 'inventory_quantity' => 43, 'price' => 13.00, 'cost' => 8.00],
            ['variant_name' => 'Small', 'sku' => '5001-WHITE-G-S', 'color' => 'White', 'size' => 'S', 'weight' => 4, 'inventory_quantity' => 501, 'price' => 13.00, 'cost' => 8.00],
            ['variant_name' => 'Medium', 'sku' => '5001-WHITE-H-M', 'color' => 'White', 'size' => 'M', 'weight' => 4, 'inventory_quantity' => 2334, 'price' => 13.00, 'cost' => 8.00],
            ['variant_name' => 'Large', 'sku' => '5001-WHITE-I-L', 'color' => 'White', 'size' => 'L', 'weight' => 5, 'inventory_quantity' => 743, 'price' => 13.00, 'cost' => 8.00],
            ['variant_name' => 'XLarge', 'sku' => '5001-WHITE-J-XL', 'color' => 'White', 'size' => 'XL', 'weight' => 5, 'inventory_quantity' => 21, 'price' => 13.00, 'cost' => 8.00],
            ['variant_name' => '2XLarge', 'sku' => '5001-WHITE-K-2XL', 'color' => 'White', 'size' => '2XL', 'weight' => 6, 'inventory_quantity' => 0, 'price' => 13.00, 'cost' => 8.00],
        ];

        foreach ($variants as $variantData) {
            ProductVariant::create(array_merge($variantData, ['product_id' => $stapleTee->id]));
        }

        // Update the main product's stock quantity to reflect total variants
        $stapleTee->update(['stock_quantity' => $stapleTee->variants()->sum('inventory_quantity')]);

        // Create another product with variants - iPhone 15 Pro in different colors
        $iphone = Product::create([
            'name' => 'iPhone 15 Pro',
            'sku' => 'IPHONE-15-PRO',
            'description' => 'Apple iPhone 15 Pro with A17 Pro chip, titanium design, and advanced camera system.',
            'price' => 999.00,
            'cost' => 750.00,
            'stock_quantity' => 0,
            'min_stock_level' => 5,
            'category' => 'Electronics',
            'brand' => 'Apple',
            'status' => 'active',
            'images' => ['products/iphone-15-pro-natural.jpg', 'products/iphone-15-pro-blue.jpg', 'products/iphone-15-pro-white.jpg'],
            'specifications' => [
                'Processor' => 'A17 Pro chip',
                'Display' => '6.1-inch Super Retina XDR',
                'Camera' => '48MP Main, 12MP Ultra Wide, 12MP Telephoto',
                'Storage' => '256GB, 512GB, 1TB',
                'Battery' => 'Up to 23 hours video playback',
            ],
            'weight' => 0.187,
            'dimensions' => '14.67 x 7.15 x 0.83 cm',
            'barcode' => '1234567890133',
            'is_featured' => true,
            'published_at' => now(),
            'website_url' => 'https://apple.com/iphone-15-pro',
            'supplier' => 'Apple',
            'product_type' => 'Smartphone',
            'fabric' => 'Titanium',
            'care_instructions' => 'Use provided cleaning cloth. Avoid abrasive materials.',
            'lead_times' => '1-2 weeks',
            'available_sizes' => '256GB, 512GB, 1TB',
            'customization_methods' => ['Engraving'],
            'model_size' => '6.1-inch',
            'starting_from_price' => 999.00,
            'minimums' => 'No minimums',
            'last_inventory_sync' => now()->subDays(2),
            'has_variants' => true,
        ]);

        // Create iPhone variants
        $iphoneVariants = [
            ['variant_name' => 'Natural Titanium 256GB', 'sku' => 'IPH15P-NAT-256', 'color' => 'Natural Titanium', 'size' => '256GB', 'weight' => 6.6, 'inventory_quantity' => 25, 'price' => 999.00, 'cost' => 750.00],
            ['variant_name' => 'Natural Titanium 512GB', 'sku' => 'IPH15P-NAT-512', 'color' => 'Natural Titanium', 'size' => '512GB', 'weight' => 6.6, 'inventory_quantity' => 15, 'price' => 1199.00, 'cost' => 900.00],
            ['variant_name' => 'Blue Titanium 256GB', 'sku' => 'IPH15P-BLU-256', 'color' => 'Blue Titanium', 'size' => '256GB', 'weight' => 6.6, 'inventory_quantity' => 30, 'price' => 999.00, 'cost' => 750.00],
            ['variant_name' => 'White Titanium 256GB', 'sku' => 'IPH15P-WHT-256', 'color' => 'White Titanium', 'size' => '256GB', 'weight' => 6.6, 'inventory_quantity' => 20, 'price' => 999.00, 'cost' => 750.00],
            ['variant_name' => 'Black Titanium 256GB', 'sku' => 'IPH15P-BLK-256', 'color' => 'Black Titanium', 'size' => '256GB', 'weight' => 6.6, 'inventory_quantity' => 0, 'price' => 999.00, 'cost' => 750.00],
        ];

        foreach ($iphoneVariants as $variantData) {
            ProductVariant::create(array_merge($variantData, ['product_id' => $iphone->id]));
        }

        $iphone->update(['stock_quantity' => $iphone->variants()->sum('inventory_quantity')]);

        // Create a simple product without variants
        Product::create([
            'name' => 'MacBook Pro 16"',
            'sku' => 'MBP-16-2024',
            'description' => 'Apple MacBook Pro with M3 Pro chip, 16-inch Liquid Retina XDR display.',
            'price' => 2499.00,
            'cost' => 1800.00,
            'stock_quantity' => 25,
            'min_stock_level' => 5,
            'category' => 'Electronics',
            'brand' => 'Apple',
            'status' => 'active',
            'images' => ['products/macbook-pro-16.jpg'],
            'specifications' => [
                'Processor' => 'M3 Pro chip',
                'Memory' => '18GB unified memory',
                'Storage' => '512GB SSD',
                'Display' => '16-inch Liquid Retina XDR',
            ],
            'weight' => 2.15,
            'dimensions' => '35.57 x 24.81 x 1.68 cm',
            'barcode' => '1234567890134',
            'is_featured' => true,
            'published_at' => now(),
            'website_url' => 'https://apple.com/macbook-pro-16',
            'supplier' => 'Apple',
            'product_type' => 'Laptop',
            'fabric' => 'Aluminum',
            'care_instructions' => 'Use soft, lint-free cloth. Avoid abrasive materials.',
            'lead_times' => '2-3 weeks',
            'available_sizes' => '16-inch',
            'customization_methods' => ['Engraving'],
            'model_size' => '16-inch',
            'starting_from_price' => 2499.00,
            'minimums' => 'No minimums',
            'last_inventory_sync' => now()->subDays(1),
            'has_variants' => false,
        ]);
    }
}
