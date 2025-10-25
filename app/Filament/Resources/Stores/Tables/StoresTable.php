<?php

namespace App\Filament\Resources\Stores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('company_logo')
                    ->label('Logo')
                    ->disk('public')
                    ->size(60)
                    ->url(fn ($record) => $record && $record->company_logo ? asset('storage/' . $record->company_logo) : null)
                    ->openUrlInNewTab()
                    ->defaultImageUrl('data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none"><rect width="60" height="60" fill="#f3f4f6" stroke="#d1d5db" stroke-width="1"/><rect x="15" y="15" width="30" height="30" fill="#9ca3af" rx="4"/><text x="30" y="35" text-anchor="middle" fill="white" font-size="12" font-family="Arial">🏪</text></svg>')),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                    ->sortable(),
                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),
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
                TernaryFilter::make('status')
                    ->label('Status')
                    ->placeholder('All stores')
                    ->trueLabel('Active stores')
                    ->falseLabel('Inactive stores'),
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
