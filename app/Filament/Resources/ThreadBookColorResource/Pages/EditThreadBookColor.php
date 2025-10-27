<?php

namespace App\Filament\Resources\ThreadBookColorResource\Pages;

use App\Filament\Resources\ThreadBookColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThreadBookColor extends EditRecord
{
    protected static string $resource = ThreadBookColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
