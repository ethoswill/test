<?php

namespace App\Filament\Resources\ThreadColorResource\Imports;

use App\Models\ThreadColor;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Actions\Imports\ImportColumn;

class ThreadColorImporter extends Importer
{
    protected static ?string $model = ThreadColor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('color_name')
                ->label('Thread Number')
                ->required()
                ->rules(['required', 'string', 'max:255'])
                ->helperText('The thread number/identifier (e.g., 1001, 1002)')
                ->example('1001'),
            ImportColumn::make('color_code')
                ->label('Color Code')
                ->required()
                ->rules(['required', 'string', 'max:255'])
                ->helperText('The color name (e.g., White, Black, Red)')
                ->example('White'),
            ImportColumn::make('image_url')
                ->label('Thread Color Image URL')
                ->rules(['nullable', 'url', 'max:500'])
                ->helperText('Full URL to the thread color swatch image (e.g., https://cdn.shopify.com/s/files/1/.../thread-white-1001.png)')
                ->example('https://cdn.shopify.com/s/files/1/0609/4752/9901/files/thread-white-1001.png'),
            ImportColumn::make('used_in')
                ->label('Used In')
                ->rules(['nullable', 'string'])
                ->helperText('List product colors where this thread is used (comma-separated, optional)')
                ->example('White T-Shirts, Cream Products'),
        ];
    }

    public function resolveRecord(): ?ThreadColor
    {
        // Check if a thread color with the same color_name exists
        return ThreadColor::firstOrNew([
            'color_name' => $this->data['color_name'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your thread color import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
