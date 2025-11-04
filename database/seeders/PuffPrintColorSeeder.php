<?php

namespace Database\Seeders;

use App\Models\PuffPrintColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PuffPrintColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            'Apple Green',
            'Beige',
            'Black',
            'Blood Orange',
            'Burgundy',
            'Camel',
            'Coral',
            'Dark Gray',
            'Dark Green',
            'Deep Pink',
            'Egglant',
            'Gray',
            'Green',
            'Indigo',
            'Lemon',
            'Lilac',
            'Mint',
            'Navy',
            'Neon Blue',
            'Neon Coral',
            'Neon Green',
            'Neon Orange',
            'Neon Pink',
            'Neon Purple',
            'Neon Yellow',
            'Olive',
            'Orange',
            'Pacific',
            'Pastel Blue',
            'Pastel Pink',
            'Red',
            'Royal Blue',
            'Salmon',
            'Sky Blue',
            'Tangerine',
            'Tiffany',
            'Yellow',
        ];

        foreach ($colors as $color) {
            PuffPrintColor::firstOrCreate(['name' => $color]);
        }
    }
}
