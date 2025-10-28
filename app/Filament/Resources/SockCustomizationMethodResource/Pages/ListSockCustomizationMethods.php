<?php

namespace App\Filament\Resources\SockCustomizationMethodResource\Pages;

use App\Filament\Resources\SockCustomizationMethodResource;
use App\Filament\Resources\SockCustomizationMethodResource\Widgets\CustomizationMethods;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListSockCustomizationMethods extends ListRecords
{
    protected static string $resource = SockCustomizationMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
