<?php

namespace App\Filament\Resources\FileManagers;

use App\Filament\Resources\FileManagers\Pages\CreateFileManager;
use App\Filament\Resources\FileManagers\Pages\EditFileManager;
use App\Filament\Resources\FileManagers\Pages\ListFileManagers;
use App\Filament\Resources\FileManagers\Pages\ViewFileManager;
use App\Filament\Resources\FileManagers\Schemas\FileManagerForm;
use App\Filament\Resources\FileManagers\Tables\FileManagersTable;
use App\Models\FileManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FileManagerResource extends Resource
{
    protected static ?string $model = FileManager::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;
    
    protected static ?string $navigationLabel = 'File Manager';
    
    protected static ?string $modelLabel = 'File';
    
    protected static ?string $pluralModelLabel = 'Files';

    public static function form(Schema $schema): Schema
    {
        return FileManagerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FileManagersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFileManagers::route('/'),
            'create' => CreateFileManager::route('/create'),
            'view' => ViewFileManager::route('/{record}'),
            'edit' => EditFileManager::route('/{record}/edit'),
        ];
    }
}
