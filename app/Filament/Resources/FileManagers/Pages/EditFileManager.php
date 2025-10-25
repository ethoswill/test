<?php

namespace App\Filament\Resources\FileManagers\Pages;

use App\Filament\Resources\FileManagers\FileManagerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFileManager extends EditRecord
{
    protected static string $resource = FileManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
