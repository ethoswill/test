<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVariant extends CreateRecord
{
    protected static string $resource = ProductVariantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pre-populate product_id if passed in URL
        if (request()->has('product_id')) {
            $data['product_id'] = request()->get('product_id');
        }
        
        return $data;
    }
}
