<?php

namespace App\Filament\Resources\SockResource\Pages;

use App\Filament\Resources\SockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSocks extends ListRecords
{
    protected static string $resource = SockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
