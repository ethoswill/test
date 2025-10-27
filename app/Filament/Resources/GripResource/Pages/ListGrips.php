<?php

namespace App\Filament\Resources\GripResource\Pages;

use App\Filament\Resources\GripResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrips extends ListRecords
{
    protected static string $resource = GripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
