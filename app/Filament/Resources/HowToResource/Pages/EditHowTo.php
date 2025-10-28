<?php

namespace App\Filament\Resources\HowToResource\Pages;

use App\Filament\Resources\HowToResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHowTo extends EditRecord
{
    protected static string $resource = HowToResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
