<?php

namespace App\Filament\Resources\SockGripResource\Pages;

use App\Filament\Resources\SockGripResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSockGrip extends EditRecord
{
    protected static string $resource = SockGripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
