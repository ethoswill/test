<?php

namespace Database\Seeders;

use App\Models\ThreadBookColor;
use Illuminate\Database\Seeder;

class ThreadBookColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'name' => 'Black',
                'color_code' => null,
                'hex_code' => '000000',
                'color_category' => 'blacks',
                'is_active' => true,
            ],
            [
                'name' => 'White',
                'color_code' => null,
                'hex_code' => 'FFFFFF',
                'color_category' => 'whites',
                'is_active' => true,
            ],
            [
                'name' => 'Heather',
                'color_code' => 'BC07',
                'hex_code' => '6B6B6B', // Gray heather - approximate
                'color_category' => 'grays',
                'is_active' => true,
            ],
            [
                'name' => 'White Heather',
                'color_code' => 'BC01',
                'hex_code' => 'F5F5F5', // Light gray heather - approximate
                'color_category' => 'neutrals',
                'is_active' => true,
            ],
            [
                'name' => 'Charcoal Heather',
                'color_code' => 'BC09',
                'hex_code' => '3A3A3A', // Dark gray heather - approximate
                'color_category' => 'grays',
                'is_active' => true,
            ],
            [
                'name' => 'Off White',
                'color_code' => 'L118',
                'hex_code' => 'FAF8F3', // Off-white cream - approximate
                'color_category' => 'whites',
                'is_active' => true,
            ],
            [
                'name' => 'Sand',
                'color_code' => 'L201',
                'hex_code' => 'DDC5A6', // Sand beige - approximate
                'color_category' => 'neutrals',
                'is_active' => true,
            ],
            [
                'name' => 'Oat Milk',
                'color_code' => 'L74',
                'hex_code' => 'E8DDC6', // Oat milk beige - approximate
                'color_category' => 'neutrals',
                'is_active' => true,
            ],
            [
                'name' => 'Caramel',
                'color_code' => 'L213',
                'hex_code' => 'C49B6B', // Caramel brown - approximate
                'color_category' => 'browns',
                'is_active' => true,
            ],
            [
                'name' => 'Tan',
                'color_code' => 'L252',
                'hex_code' => 'A0754F', // Tan brown - approximate
                'color_category' => 'browns',
                'is_active' => true,
            ],
            [
                'name' => 'Mocha',
                'color_code' => 'M75',
                'hex_code' => '7D5A3E', // Mocha brown - approximate
                'color_category' => 'browns',
                'is_active' => true,
            ],
            [
                'name' => 'Charcoal',
                'color_code' => 'D343',
                'hex_code' => '36454F', // Charcoal gray - approximate
                'color_category' => 'grays',
                'is_active' => true,
            ],
        ];

        foreach ($colors as $color) {
            ThreadBookColor::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }
    }
}
