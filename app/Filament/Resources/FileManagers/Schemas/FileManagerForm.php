<?php

namespace App\Filament\Resources\FileManagers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class FileManagerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('file_path')
                    ->label('Current File')
                    ->disabled()
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return 'No file uploaded';
                        
                        $filename = basename($state);
                        $fileType = $record && $record->mime_type ? $record->mime_type : 'unknown';
                        
                        if (str_starts_with($fileType, 'image/')) {
                            return "🖼️ $filename (Image)";
                        } elseif (str_starts_with($fileType, 'application/pdf')) {
                            return "📄 $filename (PDF)";
                        } elseif (str_starts_with($fileType, 'text/')) {
                            return "📝 $filename (Text)";
                        } else {
                            return "📁 $filename";
                        }
                    })
                    ->helperText('Current file - to change, delete this record and create a new one')
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
