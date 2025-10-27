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
            'Dark Green',
            'Dark Grey',
            'Deep Pink',
            'Eggplant',
            'Green',
            'Grey',
            'Indigo Blue',
            'Lemon Yellow',
            'Lilac',
            'Mint',
            'Navy',
            'Olive Green',
            'Orange',
            'Pacific Green',
            'Pastel Blue',
            'Pastel Pink',
            'Red',
            'Royal Blue',
            'Salmon Pink',
            'Sky Blue',
            'Tangerine',
            'Tiffany Blue',
            'White',
            'Yellow',
        ];

        foreach ($colors as $color) {
            PuffPrintColor::firstOrCreate(['name' => $color]);
        }
    }
}
