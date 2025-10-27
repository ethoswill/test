<?php

namespace App\Filament\Resources\PuffPrintColorResource\Pages;

use App\Filament\Resources\PuffPrintColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPuffPrintColor extends EditRecord
{
    protected static string $resource = PuffPrintColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
