<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\Product;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample stores
        $summerStore = Store::create([
            'name' => 'Summer Store 2024',
            'description' => 'Fresh summer styles and designs',
            'status' => true,
        ]);

        $winterStore = Store::create([
            'name' => 'Winter Store 2024',
            'description' => 'Warm and cozy winter essentials',
            'status' => true,
        ]);

        $sportsStore = Store::create([
            'name' => 'Sports & Activewear Store',
            'description' => 'Performance and comfort for active lifestyles',
            'status' => true,
        ]);

        // Get some products to assign to stores
        $products = Product::take(6)->get();

        if ($products->count() >= 3) {
            // Assign first 2 products to summer store
            $summerStore->products()->attach($products->take(2)->pluck('id'));
            
            // Assign next 2 products to winter store
            $winterStore->products()->attach($products->skip(2)->take(2)->pluck('id'));
            
            // Assign last 2 products to sports store
            $sportsStore->products()->attach($products->skip(4)->take(2)->pluck('id'));
        }
    }
}
