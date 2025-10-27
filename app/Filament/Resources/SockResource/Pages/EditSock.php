<?php

namespace App\Filament\Resources\SockResource\Pages;

use App\Filament\Resources\SockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSock extends EditRecord
{
    protected static string $resource = SockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
