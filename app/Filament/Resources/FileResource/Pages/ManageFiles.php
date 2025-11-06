<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use App\Filament\Resources\FileResource\Widgets\FilesHeader;
use App\Models\TeamNote;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Storage;

class ManageFiles extends ManageRecords
{
    protected static string $resource = FileResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            FilesHeader::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    // Ensure path is properly set when creating
                    if (isset($data['path']) && is_array($data['path'])) {
                        $data['path'] = $data['path'][0] ?? null;
                    }
                    if (isset($data['path']) && $data['path']) {
                        $data['file_name'] = basename($data['path']);
                        $data['disk'] = 'public';
                        
                        // If name is not set, use the filename from path
                        if (empty($data['name'])) {
                            $data['name'] = basename($data['path']);
                        }
                        
                        // Get file info if file exists
                        $filePath = Storage::disk('public')->path($data['path']);
                        if (file_exists($filePath)) {
                            if (!isset($data['size'])) {
                                $data['size'] = filesize($filePath);
                            }
                            if (!isset($data['mime_type'])) {
                                $data['mime_type'] = mime_content_type($filePath);
                            }
                            // Always automatically generate the URL from the file path
                            $data['url'] = Storage::disk('public')->url($data['path']);
                        }
                    }
                    return $data;
                }),
        ];

        // Add team notes edit action
        $teamNote = TeamNote::firstOrCreate(['page' => 'files'], ['content' => '']);
        
        $actions[] = Action::make('edit_team_notes')
            ->label('Edit Team Notes')
            ->icon('heroicon-o-pencil-square')
            ->color('gray')
            ->form([
                RichEditor::make('content')
                    ->label('Team Notes')
                    ->placeholder('Enter your notes here. You can use HTML tags like <h3>Heading</h3> and <br> for line breaks.')
                    ->helperText('You can use HTML tags like <h3>, <h2>, <br>, <p>, <strong>, <em>, etc.')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->default(mb_convert_encoding($teamNote->content ?: '', 'UTF-8', 'UTF-8')),
            ])
            ->action(function (array $data): void {
                // Clean and ensure UTF-8 encoding
                $content = $data['content'] ?? '';
                
                // Strip invalid UTF-8 characters
                $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
                $content = iconv('UTF-8', 'UTF-8//IGNORE', $content);
                
                $teamNote = TeamNote::firstOrNew(['page' => 'files']);
                $teamNote->content = $content;
                $teamNote->save();

                Notification::make()
                    ->title('Notes updated successfully!')
                    ->success()
                    ->send();
            })
            ->requiresConfirmation(false)
            ->modalHeading('Edit Team Notes')
            ->modalSubmitActionLabel('Save');

        return $actions;
    }


}
