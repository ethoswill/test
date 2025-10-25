<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('manage_files')
                ->label('Manage Files')
                ->icon('heroicon-o-folder')
                ->color('info')
                ->url(fn () => route('filament.admin.resources.file-managers.index'))
                ->openUrlInNewTab(),
        ];
    }
}
