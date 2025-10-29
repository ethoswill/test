<?php

namespace App\Filament\Resources\LoginInfoResource\Pages;

use App\Filament\Resources\LoginInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoginInfos extends ListRecords
{
    protected static string $resource = LoginInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
