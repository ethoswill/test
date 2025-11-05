<?php

namespace App\Filament\Resources\ThreadBookColorResource\Imports;

use App\Models\ThreadBookColor;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\ImportColumn;

class ThreadBookColorImporter extends Importer
{
    protected static ?string $model = ThreadBookColor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('color_code')
                ->label('Thread Color')
                ->rules(['nullable', 'string', 'max:255'])
                ->helperText('The thread color code (e.g., BC07, L118, M75)')
                ->example('BC07'),
            ImportColumn::make('name')
                ->label('Color Name')
                ->rules(['nullable', 'string', 'max:255'])
                ->helperText('The color name (e.g., Navy Blue, Crimson Red)')
                ->example('Navy Blue'),
        ];
    }

    public function resolveRecord(): ?ThreadBookColor
    {
        // Skip rows where both color_code and name are missing
        $colorCode = trim($this->data['color_code'] ?? '');
        $name = trim($this->data['name'] ?? '');
        
        if (empty($colorCode) && empty($name)) {
            return null; // Skip this row
        }
        
        // Try to find existing record by color_code first, then by name
        if (!empty($colorCode)) {
            return ThreadBookColor::firstOrNew([
                'color_code' => $colorCode,
            ]);
        }
        
        // If no color_code, find by name
        return ThreadBookColor::firstOrNew([
            'name' => $name,
        ]);
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Only save fields that have values
        return array_filter($data, function ($value) {
            return !empty(trim($value ?? ''));
        });
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your thread book color import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import (rows with both Thread Color and Color Name missing were skipped).';
        }

        return $body;
    }
}

