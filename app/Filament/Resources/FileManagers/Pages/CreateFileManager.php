<?php

namespace App\Filament\Resources\FileManagers\Pages;

use App\Filament\Resources\FileManagers\FileManagerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CreateFileManager extends CreateRecord
{
    protected static string $resource = FileManagerResource::class;
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('Upload File')
                    ->directory('files/uploads')
                    ->disk('public')
                    ->acceptedFileTypes(['image/*', 'application/pdf', 'text/*'])
                    ->maxSize(10240) // 10MB
                    ->helperText('Upload images, PDFs, or text files (max 10MB)')
                    ->columnSpanFull(),
                Select::make('store_id')
                    ->label('Assign to Store')
                    ->relationship('store', 'name')
                    ->searchable()
                    ->preload()
                    ->helperText('Select which store this file belongs to'),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->helperText('Optional description for this file')
                    ->columnSpanFull(),
            ]);
    }
}
