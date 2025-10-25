<?php

namespace App\Filament\Resources\FileManagers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FileManagersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Preview')
                    ->disk('public')
                    ->size(60)
                    ->url(fn ($record) => $record ? $record->file_url : null)
                    ->openUrlInNewTab()
                    ->defaultImageUrl('data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none"><rect width="60" height="60" fill="#f3f4f6" stroke="#d1d5db" stroke-width="1"/><rect x="15" y="15" width="30" height="30" fill="#9ca3af" rx="4"/><text x="30" y="35" text-anchor="middle" fill="white" font-size="12" font-family="Arial">📄</text></svg>')),
                TextColumn::make('original_name')
                    ->label('File Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->url(fn ($record) => $record && $record->isImage() ? route('file-preview', $record) : route('filament.admin.resources.file-managers.view', $record))
                    ->color('primary'),
                TextColumn::make('store.name')
                    ->label('Assigned Store')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Unassigned')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
                BadgeColumn::make('mime_type')
                    ->label('Type')
                    ->colors([
                        'success' => 'image/*',
                        'warning' => 'application/pdf',
                        'info' => 'text/*',
                        'gray' => fn ($state) => !str_starts_with($state, 'image/') && !str_starts_with($state, 'application/pdf') && !str_starts_with($state, 'text/'),
                    ])
                    ->formatStateUsing(fn ($state) => match(true) {
                        str_starts_with($state, 'image/') => 'Image',
                        str_starts_with($state, 'application/pdf') => 'PDF',
                        str_starts_with($state, 'text/') => 'Text',
                        default => 'Other'
                    }),
                TextColumn::make('file_size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => self::formatFileSize($state))
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('created_at')
                    ->label('Uploaded')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('store_id')
                    ->label('Store')
                    ->relationship('store', 'name')
                    ->placeholder('All stores'),
                SelectFilter::make('mime_type')
                    ->label('File Type')
                    ->options([
                        'image' => 'Images',
                        'application/pdf' => 'PDFs',
                        'text' => 'Text Files',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'image') {
                            return $query->where('mime_type', 'like', 'image/%');
                        }
                        if ($data['value'] === 'application/pdf') {
                            return $query->where('mime_type', 'application/pdf');
                        }
                        if ($data['value'] === 'text') {
                            return $query->where('mime_type', 'like', 'text/%');
                        }
                        return $query;
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => $record && $record->isImage()),
                ViewAction::make()
                    ->label('View Details')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn ($record) => !$record || !$record->isImage()),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    
    private static function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
