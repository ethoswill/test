<?php

namespace App\Filament\Resources\FileManagers\Pages;

use App\Filament\Resources\FileManagers\FileManagerResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFileManager extends ViewRecord
{
    protected static string $resource = FileManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
