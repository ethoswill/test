<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'MacBook Pro 16"',
                'sku' => 'MBP-16-2024',
                'description' => 'Apple MacBook Pro with M3 Pro chip, 16-inch Liquid Retina XDR display, 18GB unified memory, and 512GB SSD storage.',
                'price' => 2499.00,
                'cost' => 1800.00,
                'stock_quantity' => 25,
                'min_stock_level' => 5,
                'category' => 'Electronics',
                'brand' => 'Apple',
                'status' => 'active',
                'images' => ['macbook-pro-16.jpg'],
                'specifications' => [
                    'Processor' => 'M3 Pro chip',
                    'Memory' => '18GB unified memory',
                    'Storage' => '512GB SSD',
                    'Display' => '16-inch Liquid Retina XDR',
                    'Ports' => '3x Thunderbolt 4, HDMI, SD card slot, MagSafe 3'
                ],
                'weight' => 2.15,
                'dimensions' => '35.57 x 24.81 x 1.68 cm',
                'barcode' => '1234567890123',
                'is_featured' => true,
                'published_at' => now(),
            ],
            [
                'name' => 'iPhone 15 Pro',
                'sku' => 'IPH-15-PRO-256',
                'description' => 'Apple iPhone 15 Pro with A17 Pro chip, 6.1-inch Super Retina XDR display, 256GB storage, and titanium design.',
                'price' => 999.00,
                'cost' => 750.00,
                'stock_quantity' => 50,
                'min_stock_level' => 10,
                'category' => 'Electronics',
                'brand' => 'Apple',
                'status' => 'active',
                'images' => ['iphone-15-pro.jpg'],
                'specifications' => [
                    'Processor' => 'A17 Pro chip',
                    'Storage' => '256GB',
                    'Display' => '6.1-inch Super Retina XDR',
                    'Camera' => '48MP Main, 12MP Ultra Wide, 12MP Telephoto',
                    'Battery' => 'Up to 23 hours video playback'
                ],
                'weight' => 0.187,
                'dimensions' => '14.67 x 7.15 x 0.83 cm',
                'barcode' => '1234567890124',
                'is_featured' => true,
                'published_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'sku' => 'SGS-24-ULTRA-512',
                'description' => 'Samsung Galaxy S24 Ultra with Snapdragon 8 Gen 3, 6.8-inch Dynamic AMOLED 2X display, 512GB storage, and S Pen.',
                'price' => 1299.99,
                'cost' => 950.00,
                'stock_quantity' => 30,
                'min_stock_level' => 8,
                'category' => 'Electronics',
                'brand' => 'Samsung',
                'status' => 'active',
                'images' => ['galaxy-s24-ultra.jpg'],
                'specifications' => [
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'Storage' => '512GB',
                    'Display' => '6.8-inch Dynamic AMOLED 2X',
                    'Camera' => '200MP Main, 50MP Periscope, 12MP Ultra Wide',
                    'S Pen' => 'Included'
                ],
                'weight' => 0.232,
                'dimensions' => '16.24 x 7.9 x 0.88 cm',
                'barcode' => '1234567890125',
                'is_featured' => false,
                'published_at' => now(),
            ],
            [
                'name' => 'Nike Air Max 270',
                'sku' => 'NAM-270-BLK-10',
                'description' => 'Nike Air Max 270 men\'s running shoes with Max Air unit and breathable mesh upper.',
                'price' => 150.00,
                'cost' => 80.00,
                'stock_quantity' => 100,
                'min_stock_level' => 20,
                'category' => 'Clothing',
                'brand' => 'Nike',
                'status' => 'active',
                'images' => ['nike-air-max-270.jpg'],
                'specifications' => [
                    'Type' => 'Running Shoes',
                    'Upper' => 'Breathable mesh',
                    'Sole' => 'Rubber with Max Air unit',
                    'Sizes' => '7-13 US',
                    'Colors' => 'Black, White, Blue'
                ],
                'weight' => 0.8,
                'dimensions' => '32 x 22 x 12 cm',
                'barcode' => '1234567890126',
                'is_featured' => false,
                'published_at' => now(),
            ],
            [
                'name' => 'The Great Gatsby',
                'sku' => 'BOOK-GATSBY-PB',
                'description' => 'The Great Gatsby by F. Scott Fitzgerald - Classic American novel in paperback edition.',
                'price' => 12.99,
                'cost' => 6.50,
                'stock_quantity' => 200,
                'min_stock_level' => 50,
                'category' => 'Books',
                'brand' => 'Penguin Classics',
                'status' => 'active',
                'images' => ['great-gatsby.jpg'],
                'specifications' => [
                    'Author' => 'F. Scott Fitzgerald',
                    'Pages' => '180',
                    'Format' => 'Paperback',
                    'Language' => 'English',
                    'ISBN' => '978-0-14-118263-6'
                ],
                'weight' => 0.2,
                'dimensions' => '19.8 x 12.9 x 1.2 cm',
                'barcode' => '1234567890127',
                'is_featured' => false,
                'published_at' => now(),
            ],
            [
                'name' => 'KitchenAid Stand Mixer',
                'sku' => 'KASM-ARTISAN-RED',
                'description' => 'KitchenAid Artisan Series 5-Quart Stand Mixer in Empire Red with dough hook, flat beater, and wire whip.',
                'price' => 379.99,
                'cost' => 250.00,
                'stock_quantity' => 15,
                'min_stock_level' => 3,
                'category' => 'Home & Garden',
                'brand' => 'KitchenAid',
                'status' => 'active',
                'images' => ['kitchenaid-mixer.jpg'],
                'specifications' => [
                    'Capacity' => '5-Quart',
                    'Power' => '325 Watts',
                    'Attachments' => 'Dough hook, flat beater, wire whip',
                    'Colors' => 'Empire Red',
                    'Warranty' => '1 year'
                ],
                'weight' => 12.7,
                'dimensions' => '38.1 x 30.5 x 30.5 cm',
                'barcode' => '1234567890128',
                'is_featured' => true,
                'published_at' => now(),
            ],
            [
                'name' => 'Wilson Pro Staff Tennis Racket',
                'sku' => 'WPS-97-STRUNG',
                'description' => 'Wilson Pro Staff 97 tennis racket with 16x19 string pattern, perfect for advanced players.',
                'price' => 249.00,
                'cost' => 150.00,
                'stock_quantity' => 40,
                'min_stock_level' => 10,
                'category' => 'Sports',
                'brand' => 'Wilson',
                'status' => 'active',
                'images' => ['wilson-pro-staff.jpg'],
                'specifications' => [
                    'Head Size' => '97 sq in',
                    'String Pattern' => '16x19',
                    'Weight' => '315g',
                    'Balance' => '320mm',
                    'Grip Size' => '4 3/8'
                ],
                'weight' => 0.315,
                'dimensions' => '68.6 x 31.8 x 2.5 cm',
                'barcode' => '1234567890129',
                'is_featured' => false,
                'published_at' => now(),
            ],
            [
                'name' => 'LEGO Creator Expert Modular Building',
                'sku' => 'LEGO-MODULAR-BANK',
                'description' => 'LEGO Creator Expert Downtown Diner modular building set with 2,500+ pieces.',
                'price' => 179.99,
                'cost' => 100.00,
                'stock_quantity' => 20,
                'min_stock_level' => 5,
                'category' => 'Toys',
                'brand' => 'LEGO',
                'status' => 'active',
                'images' => ['lego-modular-building.jpg'],
                'specifications' => [
                    'Pieces' => '2,500+',
                    'Age' => '16+',
                    'Theme' => 'Creator Expert',
                    'Dimensions' => '25.5 x 25.5 x 15 cm',
                    'Minifigures' => '4'
                ],
                'weight' => 1.2,
                'dimensions' => '25.5 x 25.5 x 15 cm',
                'barcode' => '1234567890130',
                'is_featured' => false,
                'published_at' => now(),
            ],
            [
                'name' => 'Discontinued Product Example',
                'sku' => 'DISC-PROD-001',
                'description' => 'This is an example of a discontinued product for testing purposes.',
                'price' => 99.99,
                'cost' => 60.00,
                'stock_quantity' => 0,
                'min_stock_level' => 0,
                'category' => 'Electronics',
                'brand' => 'Example Brand',
                'status' => 'discontinued',
                'images' => [],
                'specifications' => [],
                'weight' => 0.5,
                'dimensions' => '20 x 15 x 5 cm',
                'barcode' => '1234567890131',
                'is_featured' => false,
                'published_at' => now()->subMonths(6),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
