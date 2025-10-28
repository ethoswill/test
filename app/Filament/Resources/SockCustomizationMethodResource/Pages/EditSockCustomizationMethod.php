<?php

namespace App\Filament\Resources\SockCustomizationMethodResource\Pages;

use App\Filament\Resources\SockCustomizationMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSockCustomizationMethod extends EditRecord
{
    protected static string $resource = SockCustomizationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
