<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Basic Information
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('supplier_code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('website_url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('hs_code')
                    ->label('HS Code')
                    ->maxLength(255),
                TextInput::make('parent_product')
                    ->maxLength(255),
                Select::make('status')
                    ->options([
                        'Supplier Product' => 'Supplier Product',
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Discontinued' => 'Discontinued',
                    ])
                    ->required()
                    ->default('Supplier Product'),
                TextInput::make('product_type')
                    ->maxLength(255),

                // Product Details
                Textarea::make('care_instructions')
                    ->columnSpanFull(),
                TextInput::make('fabric')
                    ->maxLength(255),
                TextInput::make('how_it_fits')
                    ->maxLength(255),
                TextInput::make('lead_times')
                    ->maxLength(255),
                TextInput::make('available_sizes')
                    ->maxLength(255),
                TextInput::make('customization_methods')
                    ->maxLength(255),
                TextInput::make('model_size')
                    ->maxLength(255),
                TextInput::make('minimums')
                    ->maxLength(255),

                // Pricing
                TextInput::make('starting_from_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('ethos_cost_price')
                    ->label('Ethos Cost Price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('customer_b2b_price')
                    ->label('Customer B2B Price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('customer_dtc_price')
                    ->label('Customer DTC Price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('franchisee_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('cost_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('retail_price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('minimum_order_quantity')
                    ->numeric(),

                // Sock Customization
                TextInput::make('thread_color')
                    ->maxLength(255),
                TextInput::make('thread_colors')
                    ->maxLength(255),
                TextInput::make('logo_style')
                    ->maxLength(255),
                TextInput::make('embroidered_logo_thread_colors')
                    ->maxLength(255),
                TextInput::make('grip_color')
                    ->maxLength(255),
                Toggle::make('split_across_variants')
                    ->label('Split Across Variants'),

                // Relationships
                Select::make('store_id')
                    ->relationship('store', 'name')
                    ->searchable(),
                Select::make('vendor_id')
                    ->relationship('vendor', 'name')
                    ->searchable(),

                // Media
                FileUpload::make('media')
                    ->multiple()
                    ->image()
                    ->directory('products')
                    ->columnSpanFull(),
            ]);
    }
}
