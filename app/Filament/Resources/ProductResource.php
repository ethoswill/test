<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Repeater;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'CAD Library';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $pluralModelLabel = 'CAD Library';

    protected static ?string $navigationGroup = 'Design Tools';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product Details')
                    ->schema([
                        Grid::make(2)
            ->schema([
                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true),
                                Forms\Components\TextInput::make('website_url')
                                    ->label('Website URL')
                                    ->url()
                                    ->maxLength(500),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('supplier')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('product_type')
                                    ->label('Product Type')
                                    ->maxLength(100),
                            ]),
                        Forms\Components\ColorPicker::make('base_color')
                            ->label('Base Color (Illustrator)')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('base_color_hex', $state);
                                }
                            })
                            ->helperText('Base color used in Illustrator for this product')
                            ->extraAttributes(['style' => 'border: 1px solid #ccc; border-radius: 4px;'])
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('base_color_hex')
                            ->default('#FFFFFF'),
                        SpatieMediaLibraryFileUpload::make('cad_download')
                            ->collection('cad_download')
                            ->label('CAD Download')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->helperText('Upload a PDF or image file for the CAD reference')
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->imagePreviewHeight('200')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('cad_download')
                            ->label('CAD Download URL (Alternative)')
                            ->url()
                            ->maxLength(500)
                            ->helperText('Or enter a direct URL to an external CAD file')
                            ->placeholder('https://example.com/path/to/file.pdf')
                            ->visible(fn ($record) => !$record?->getMedia('cad_download')->count())
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\ColorPicker::make('tone_on_tone_darker')
                                    ->label('Tone on Tone Hex Code (Darker Color)')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('tone_on_tone_darker_hex', $state);
                                        }
                                    })
                                    ->helperText('Select darker tone color')
                                    ->extraAttributes(['style' => 'border: 1px solid #ccc; border-radius: 4px;']),
                                Forms\Components\Hidden::make('tone_on_tone_darker_hex')
                                    ->default('#000000'),
                                Forms\Components\ColorPicker::make('tone_on_tone_lighter')
                                    ->label('Tone on Tone Hex Code (Lighter Color)')
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('tone_on_tone_lighter_hex', $state);
                                        }
                                    })
                                    ->helperText('Select lighter tone color')
                                    ->extraAttributes(['style' => 'border: 1px solid #ccc; border-radius: 4px;']),
                                Forms\Components\Hidden::make('tone_on_tone_lighter_hex')
                                    ->default('#FFFFFF'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Product Media')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->maxFiles(10)
                            ->directory('products')
                            ->disk('public')
                    ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Product Notes')
                            ->rows(4)
                            ->placeholder('Add any additional notes about this product...')
                    ->columnSpanFull(),
                    ])
                    ->collapsible(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->product_type)
                    ->url(fn ($record) => route('filament.admin.resources.products.view', $record))
                    ->color('primary'),
                Tables\Columns\TextColumn::make('supplier')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('base_color')
                    ->label('Base Color')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return new \Illuminate\Support\HtmlString(
                            '<div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 20px; height: 20px; background-color: ' . $state . '; border: 1px solid #ccc; border-radius: 3px;"></div>
                                <span>' . $state . '</span>
                            </div>'
                        );
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('tone_on_tone_darker')
                    ->label('Tone on Tone (Darker)')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return new \Illuminate\Support\HtmlString(
                            '<div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 20px; height: 20px; background-color: ' . $state . '; border: 1px solid #ccc; border-radius: 3px;"></div>
                                <span>' . $state . '</span>
                            </div>'
                        );
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('tone_on_tone_lighter')
                    ->label('Tone on Tone (Lighter)')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return 'N/A';
                        return new \Illuminate\Support\HtmlString(
                            '<div style="display: flex; align-items: center; gap: 8px;">
                                <div style="width: 20px; height: 20px; background-color: ' . $state . '; border: 1px solid #ccc; border-radius: 3px;"></div>
                                <span>' . $state . '</span>
                            </div>'
                        );
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('cad_download_link')
                    ->label('CAD Download')
                    ->html()
                    ->formatStateUsing(function ($state, $record) {
                        // Check Media Library first
                        $mediaFiles = $record->getMedia('cad_download');
                        if ($mediaFiles->isNotEmpty()) {
                            $firstMedia = $mediaFiles->first();
                            $url = $firstMedia->getUrl();
                            
                            return new \Illuminate\Support\HtmlString(
                                '<a href="' . $url . '" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                    <svg style="width: 16px; height: 16px; display: inline-block; margin-right: 4px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    View CAD
                                </a>'
                            );
                        }
                        
                        // Fallback to legacy cad_download field
                        $cadUrl = $record->cad_download;
                        if (!$cadUrl) {
                            return 'No CAD';
                        }
                        
                        $url = filter_var($cadUrl, FILTER_VALIDATE_URL) ? $cadUrl : asset('storage/' . $cadUrl);
                        
                        return new \Illuminate\Support\HtmlString(
                            '<a href="' . $url . '" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                <svg style="width: 16px; height: 16px; display: inline-block; margin-right: 4px; vertical-align: middle;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                View CAD
                            </a>'
                        );
                    }),
                Tables\Columns\TextColumn::make('fabric')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('available_sizes')
                    ->label('Sizes')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_inventory_sync')
                    ->label('Last Sync')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'discontinued' => 'Discontinued',
                        'supplier_product' => 'Supplier Product',
                    ]),
                SelectFilter::make('supplier')
                    ->options(fn () => Product::distinct()->pluck('supplier', 'supplier')->filter()),
                SelectFilter::make('product_type')
                    ->options(fn () => Product::distinct()->pluck('product_type', 'product_type')->filter()),
                TernaryFilter::make('is_featured')
                    ->label('Featured Products')
                    ->boolean()
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured')
                    ->native(false),
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', '<=', 10))
                    ->toggle(),
                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', 0))
                    ->toggle(),
            ])
                ->actions([])
            ->headerActions([
                Tables\Actions\Action::make('google_sheets_template')
                    ->label('Google Sheets Template')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {
                        Notification::make()
                            ->title('Google Sheets Template Instructions')
                            ->body('
                                <div class="space-y-2">
                                    <p><strong>To use Google Sheets sync:</strong></p>
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Create a new Google Sheet</li>
                                        <li>Make it publicly viewable (Share → Anyone with the link can view)</li>
                                        <li>Use these column headers in row 1:</li>
                                    </ol>
                                    <div class="bg-gray-100 p-2 rounded text-sm font-mono">
                                        name, supplier, product_type, website_url, base_color, tone_on_tone_darker, tone_on_tone_lighter, notes
                                    </div>
                                    <p class="text-sm"><strong>Sample data:</strong></p>
                                    <div class="bg-gray-100 p-2 rounded text-sm font-mono">
                                        Sample Product 1, Supplier A, T-Shirt, https://example.com, #ffffff, #e8e8e8, #dedede, Sample notes<br>
                                        Sample Product 2, Supplier B, Hoodie, https://example.com, #000000, #333333, #666666, Another sample
                                    </div>
                                    <p class="text-sm"><strong>Note:</strong> You need a Google API key configured in your .env file (GOOGLE_API_KEY)</p>
                                </div>
                            ')
                            ->persistent()
                            ->send();
                    })
                    ->color('gray'),
                Tables\Actions\Action::make('google_sheets_sync')
                    ->label('Sync from Google Sheets')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->form([
                        Forms\Components\TextInput::make('google_sheets_url')
                            ->label('Google Sheets URL')
                            ->required()
                            ->url()
                            ->default('https://docs.google.com/spreadsheets/d/1hq_9x_iKVz2gRLu1__yQUlmBBdJ0k8XkO8OW78s8DFA/edit?gid=206475597#gid=206475597')
                            ->helperText('Your CAD Product Database Google Sheet URL'),
                        Forms\Components\TextInput::make('sheet_range')
                            ->label('Sheet Range (Optional)')
                            ->default('A1:I10')
                            ->helperText('Range to import (A1:I10 - Ethos ID in column A, then your 8 product fields).'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $googleSheetsService = new \App\Services\GoogleSheetsService();
                            
                            // Extract spreadsheet ID from URL
                            $spreadsheetId = $googleSheetsService->extractSpreadsheetId($data['google_sheets_url']);
                            
                            if (!$spreadsheetId) {
                                Notification::make()
                                    ->title('Invalid Google Sheets URL')
                                    ->danger()
                                    ->send();
                                return;
                            }
                            
                            // Read data from Google Sheets
                            $sheetData = $googleSheetsService->readSheet($spreadsheetId, $data['sheet_range'] ?? 'A:Z');
                            
                            if (empty($sheetData)) {
                                Notification::make()
                                    ->title('No data found in Google Sheet')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            // Parse products from sheet data
                            $products = $googleSheetsService->parseProductsFromSheet($sheetData);
                            
                            if (empty($products)) {
                                Notification::make()
                                    ->title('No valid products found in Google Sheet')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            // Import products
                            $imported = 0;
                            $errors = [];
                            
                            foreach ($products as $index => $productData) {
                                try {
                                    // Add default values for all required fields
                                    $productData = array_merge([
                                        'status' => 'active',
                                        'stock_quantity' => 0,
                                        'min_stock_level' => 0,
                                        'is_featured' => false,
                                        'has_variants' => false,
                                        'price' => 0.00,  // Required field
                                        'cost' => 0.00,   // Required field
                                    ], $productData);
                                    
                                    // Remove empty values
                                    $productData = array_filter($productData, function($value) {
                                        return $value !== null && $value !== '';
                                    });
                                    
                                    if (isset($productData['name']) && $productData['name']) {
                                        Product::create($productData);
                                        $imported++;
                                    }
                                } catch (\Exception $e) {
                                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                                }
                            }
                            
                            $message = "Successfully imported {$imported} products from Google Sheets.";
                            if (!empty($errors)) {
                                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 5));
                                if (count($errors) > 5) {
                                    $message .= " and " . (count($errors) - 5) . " more errors.";
                                }
                            }
                            
                            Notification::make()
                                ->title($message)
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error syncing from Google Sheets: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalWidth('2xl'),
                Tables\Actions\Action::make('quick_refresh')
                    ->label('Quick Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function () {
                        try {
                            $googleSheetsService = new \App\Services\GoogleSheetsService();
                            
                            // Use your saved Google Sheet settings
                            $spreadsheetId = '1hq_9x_iKVz2gRLu1__yQUlmBBdJ0k8XkO8OW78s8DFA';
                            $sheetRange = 'A:Z';
                            
                            // Read data from Google Sheets
                            $sheetData = $googleSheetsService->readSheet($spreadsheetId, $sheetRange);
                            
                            if (empty($sheetData)) {
                                Notification::make()
                                    ->title('No data found in Google Sheet')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            // Parse products from sheet data
                            $products = $googleSheetsService->parseProductsFromSheet($sheetData);
                            
                            if (empty($products)) {
                                Notification::make()
                                    ->title('No valid products found in Google Sheet')
                                    ->warning()
                                    ->send();
                                return;
                            }
                            
                            // Clear existing products first (optional - remove this if you want to keep existing)
                            // Product::truncate();
                            
                            // Import products
                            $imported = 0;
                            $updated = 0;
                            $errors = [];
                            
                            foreach ($products as $index => $productData) {
                                try {
                                    // Add default values for all required fields
                                    $productData = array_merge([
                                        'status' => 'active',
                                        'stock_quantity' => 0,
                                        'min_stock_level' => 0,
                                        'is_featured' => false,
                                        'has_variants' => false,
                                        'price' => 0.00,
                                        'cost' => 0.00,
                                    ], $productData);
                                    
                                    // Remove only null values, keep empty strings and valid data
                                    $productData = array_filter($productData, function($value) {
                                        return $value !== null;
                                    });
                                    
                                    if (isset($productData['name']) && $productData['name']) {
                                        // Generate consistent Ethos ID for matching (same Ethos ID for same product)
                                        $ethosId = $productData['sku'] ?? $this->generateConsistentEthosId($productData['name'], $productData['supplier'] ?? '');
                                        $productData['sku'] = $ethosId;
                                        
                                        // Check if product already exists by Ethos ID (most reliable)
                                        $existingProduct = Product::where('sku', $ethosId)->first();
                                        
                                        if ($existingProduct) {
                                            // Update existing product with new data
                                            $existingProduct->update($productData);
                                            $updated++;
                                        } else {
                                            // Create new product
                                            Product::create($productData);
                                            $imported++;
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                                }
                            }
                            
                            $message = "Refresh complete! Imported {$imported} new products, updated {$updated} existing products.";
                            if (!empty($errors)) {
                                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                                if (count($errors) > 3) {
                                    $message .= " and " . (count($errors) - 3) . " more errors.";
                                }
                            }
                            
                            Notification::make()
                                ->title($message)
                                ->success()
                                ->send();
                                
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error refreshing from Google Sheets: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Refresh Product Data')
                    ->modalDescription('This will sync the latest data from your Google Sheet. Existing products will be updated, new products will be added.')
                    ->modalSubmitActionLabel('Refresh Now'),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    private function findBestMatch(array $headers, array $possibleNames): ?string
    {
        foreach ($possibleNames as $name) {
            foreach ($headers as $header) {
                if (strtolower($header) === strtolower($name)) {
                    return $header;
                }
            }
        }
        
        // Try partial matches
        foreach ($possibleNames as $name) {
            foreach ($headers as $header) {
                if (strpos(strtolower($header), strtolower($name)) !== false) {
                    return $header;
                }
            }
        }
        
        return null;
    }

    private function generateConsistentEthosId(string $productName, string $supplier = ''): string
    {
        // Create a consistent Ethos ID based on product name and supplier
        // This ensures the same product always gets the same Ethos ID
        $hash = md5($productName . $supplier);
        $numericHash = hexdec(substr($hash, 0, 8));
        
        // Convert to 10-digit format starting from 1
        $ethosNumber = ($numericHash % 9999999999) + 1;
        
        return 'EiD' . str_pad($ethosNumber, 10, '0', STR_PAD_LEFT);
    }

    private function getNextEthosId(): string
    {
        // Get the highest existing Ethos ID number
        $lastProduct = Product::where('sku', 'like', 'EiD%')
            ->orderByRaw('CAST(SUBSTRING(sku, 4) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->sku, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'EiD' . str_pad($nextNumber, 10, '0', STR_PAD_LEFT);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Use a more efficient count query instead of loading all models
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        // Use a more efficient count query
        $lowStockCount = static::getModel()::whereRaw('stock_quantity <= min_stock_level')->count();
        return $lowStockCount > 0 ? 'gray' : null;
    }
}
