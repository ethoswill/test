<?php

namespace App\Filament\Resources\FileResource\Pages;

use App\Filament\Resources\FileResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Storage;

class ManageFiles extends ManageRecords
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
    }


}
