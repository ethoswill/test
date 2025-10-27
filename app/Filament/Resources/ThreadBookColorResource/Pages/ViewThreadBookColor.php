<?php

namespace App\Filament\Resources\ThreadBookColorResource\Pages;

use App\Filament\Resources\ThreadBookColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewThreadBookColor extends ViewRecord
{
    protected static string $resource = ThreadBookColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
