<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;
use App\Filament\Resources\ProductVariantResource\RelationManagers;
use App\Models\ProductVariant;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Ethos ID\'s';

    protected static ?string $modelLabel = 'Ethos ID';

    protected static ?string $pluralModelLabel = 'Ethos ID\'s';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Products';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $lowStockCount = static::getModel()::lowStock()->count();
        $outOfStockCount = static::getModel()::where('inventory_quantity', 0)->count();
        
        if ($outOfStockCount > 0) return 'danger';
        if ($lowStockCount > 0) return 'warning';
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ethos ID Information')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => request()->get('product_id'))
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sku')
                                    ->required()
                                    ->maxLength(100),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('variant_name')
                                    ->label('Ethos ID Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Small, Medium, Large'),
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('e.g., PROD-SM-WHITE'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('color')
                                    ->maxLength(50)
                                    ->placeholder('e.g., White, Black'),
                                Forms\Components\TextInput::make('size')
                                    ->maxLength(20)
                                    ->placeholder('e.g., S, M, L'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Pricing & Inventory')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->placeholder('0.00'),
                                Forms\Components\TextInput::make('cost')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->placeholder('0.00'),
                                Forms\Components\Placeholder::make('profit_margin')
                                    ->label('Profit Margin')
                                    ->content(fn ($record) => $record?->profit_margin ? $record->profit_margin . '%' : 'N/A'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('inventory_quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->placeholder('0'),
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->suffix('oz')
                                    ->placeholder('0.00'),
                            ]),
                        Forms\Components\TextInput::make('barcode')
                            ->maxLength(50)
                            ->placeholder('Barcode'),
                    ])
                    ->columns(2),

                Section::make('Status & Attributes')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Deactivate to hide this variant from customers'),
                        Forms\Components\KeyValue::make('attributes')
                            ->keyLabel('Attribute')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Attribute')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('variant_name')
                    ->label('Ethos ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('SKU copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('color')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('size')
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('cost')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('inventory_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => 
                        $record->isOutOfStock() ? 'danger' : 
                        ($record->isLowStock() ? 'warning' : 'success')
                    ),
                Tables\Columns\TextColumn::make('weight')
                    ->numeric()
                    ->suffix(' oz')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('color')
                    ->options(fn () => ProductVariant::distinct()->pluck('color', 'color')->filter()),
                SelectFilter::make('size')
                    ->options(fn () => ProductVariant::distinct()->pluck('size', 'size')->filter()),
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query): Builder => $query->lowStock())
                    ->toggle(),
                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('inventory_quantity', 0))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (ProductVariant $record) {
                        $newVariant = $record->replicate();
                        $newVariant->variant_name = $record->variant_name . ' (Copy)';
                        $newVariant->sku = $record->sku . '-COPY';
                        $newVariant->save();
                        
                        Notification::make()
                            ->title('Ethos ID duplicated successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit' => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}
