<?php

namespace App\Filament\Resources\SockGripResource\Pages;

use App\Filament\Resources\SockGripResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSockGrip extends ViewRecord
{
    protected static string $resource = SockGripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

