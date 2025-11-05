<?php

namespace App\Filament\Resources\SockResource\Pages;

use App\Filament\Resources\SockResource;
use App\Filament\Resources\SockResource\Widgets\SockGallery;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSock extends ViewRecord
{
    protected static string $resource = SockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            SockGallery::class,
        ];
    }
}
