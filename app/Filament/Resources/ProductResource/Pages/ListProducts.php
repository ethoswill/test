<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Response;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function downloadCsvTemplate()
    {
        $headers = [
            'name',
            'sku',
            'supplier',
            'product_type',
            'website_url',
            'base_color',
            'tone_on_tone_darker',
            'tone_on_tone_lighter',
            'notes',
            'fabric',
            'available_sizes',
            'price',
            'cost',
            'stock_quantity',
            'min_stock_level',
            'status',
            'description',
            'category',
            'brand',
            'weight',
            'dimensions',
            'barcode',
            'is_featured',
            'hs_code',
            'parent_product',
            'care_instructions',
            'lead_times',
            'customization_methods',
            'model_size',
            'starting_from_price',
            'minimums',
            'has_variants',
            'cad_download',
        ];
        
        // Create CSV content with headers
        $csvContent = implode(',', $headers) . "\n";
        
        // Add sample row with example data
        $sampleRow = [
            'Sample Product',
            'SKU-12345',
            'Sample Supplier',
            'T-Shirt',
            'https://example.com/product',
            '#ffffff',
            '#e8e8e8',
            '#f7f7f7',
            'Sample notes',
            'Cotton',
            'S,M,L,XL',
            '29.99',
            '15.00',
            '100',
            '10',
            'active',
            'Product description',
            'Apparel',
            'Brand Name',
            '0.5',
            '10x12x2',
            '123456789',
            'false',
            '',
            '',
            'Machine wash',
            '2-3 weeks',
            '',
            'M',
            '29.99',
            '',
            'false',
            '',
        ];
        $csvContent .= implode(',', array_map(function($value) {
            // Escape commas and quotes in CSV
            if (strpos($value, ',') !== false || strpos($value, '"') !== false || strpos($value, "\n") !== false) {
                return '"' . str_replace('"', '""', $value) . '"';
            }
            return $value;
        }, $sampleRow)) . "\n";
        
        return Response::streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, 'product_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
