<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->rows(4)
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Enable or disable this store'),
                Select::make('company_logo')
                    ->label('Company Logo')
                    ->options(function () {
                        // Get all image files from FileManager
                        $files = \App\Models\FileManager::where('mime_type', 'like', 'image/%')
                            ->whereNotNull('file_path')
                            ->get()
                            ->mapWithKeys(function ($file) {
                                return [$file->file_path => $file->original_name . ' (' . $file->file_size_formatted . ')'];
                            })
                            ->toArray();
                        
                        return $files ?: ['none' => 'No images available'];
                    })
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a logo image')
                    ->helperText('Choose from uploaded images or upload new ones via File Manager')
                    ->columnSpanFull(),
            ]);
    }
}
