<?php

namespace App\Filament\Resources\GripResource\Pages;

use App\Filament\Resources\GripResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGrip extends ViewRecord
{
    protected static string $resource = GripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
