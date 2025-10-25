<?php

namespace App\Filament\Resources\FileManagers\Pages;

use App\Filament\Resources\FileManagers\FileManagerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFileManagers extends ListRecords
{
    protected static string $resource = FileManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
