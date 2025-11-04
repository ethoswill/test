<?php

namespace App\Filament\Resources\ThreadColorResource\Pages;

use App\Filament\Resources\ThreadColorResource;
use App\Filament\Resources\ThreadColorResource\Imports\ThreadColorImporter;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\GoogleSheetsService;
use App\Services\GoogleDriveService;
use App\Models\ThreadColor;
use Filament\Forms;

class ManageThreadColors extends ManageRecords
{
    protected static string $resource = ThreadColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ImportAction::make()
                ->importer(ThreadColorImporter::class),
        ];
    }
}