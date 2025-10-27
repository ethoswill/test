<?php

namespace App\Filament\Resources\DtfInHousePrintResource\Pages;

use App\Filament\Resources\DtfInHousePrintResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDtfInHousePrint extends EditRecord
{
    protected static string $resource = DtfInHousePrintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
