<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier_code')
                    ->label('Supplier Code')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Active',
                        'warning' => 'Supplier Product',
                        'danger' => 'Inactive',
                        'secondary' => 'Discontinued',
                    ])
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product_type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starting_from_price')
                    ->label('Starting Price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('retail_price')
                    ->label('Retail Price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('fabric')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('available_sizes')
                    ->label('Sizes')
                    ->searchable()
                    ->limit(20),
                IconColumn::make('split_across_variants')
                    ->label('Split Variants')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Supplier Product' => 'Supplier Product',
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Discontinued' => 'Discontinued',
                    ]),
                SelectFilter::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->searchable(),
                SelectFilter::make('product_type')
                    ->options(function () {
                        return \App\Models\Product::distinct()
                            ->pluck('product_type', 'product_type')
                            ->filter()
                            ->toArray();
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
