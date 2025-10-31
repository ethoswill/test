<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThreadColor;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

class SyncThreadColorImages extends Command
{
    protected $signature = 'thread-colors:sync-images {folderUrl}';
    
    protected $description = 'Sync thread color images from Google Drive folder';

    public function handle()
    {
        $folderUrl = $this->argument('folderUrl');
        
        $driveService = new GoogleDriveService();
        $folderId = $driveService->extractFolderIdFromUrl($folderUrl);
        
        if (!$folderId) {
            $this->error('Invalid Google Drive folder URL');
            return 1;
        }
        
        $this->info("Extracted folder ID: {$folderId}");
        $this->info("Fetching images from Google Drive...");
        
        try {
            $files = $driveService->getFolderContents($folderId);
            $this->info("Found " . count($files) . " files in folder");
            
            $updated = 0;
            $skipped = 0;
            
            foreach ($files as $file) {
                $filename = $file->getName();
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                // Only process image files
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    continue;
                }
                
                // Extract thread color code from filename (e.g., "Image672.png" -> "672")
                preg_match('/(\d+)/', pathinfo($filename, PATHINFO_FILENAME), $matches);
                
                if (empty($matches[1])) {
                    $this->warn("Could not extract thread color code from: {$filename}");
                    $skipped++;
                    continue;
                }
                
                $colorCode = $matches[1];
                
                // Generate a viewable URL from the file ID
                // Use the uc?export=view format for embeddable images
                $imageUrl = "https://drive.google.com/uc?export=view&id={$file->getId()}";
                
                // Find and update the thread color
                $threadColor = ThreadColor::where('color_code', $colorCode)
                    ->orWhere('color_name', $colorCode)
                    ->first();
                
                if ($threadColor) {
                    // Only update if URL is different
                    if ($threadColor->image_url !== $imageUrl) {
                        $threadColor->image_url = $imageUrl;
                        $threadColor->save();
                        $this->info("Updated: {$colorCode} - {$filename}");
                        $updated++;
                    } else {
                        $this->line("Skipped (already current): {$colorCode}");
                        $skipped++;
                    }
                } else {
                    // Create new thread color if it doesn't exist
                    ThreadColor::create([
                        'color_name' => $colorCode,
                        'color_code' => $colorCode,
                        'image_url' => $imageUrl,
                    ]);
                    $this->info("Created: {$colorCode} - {$filename}");
                    $updated++;
                }
            }
            
            $this->newLine();
            $this->info("Sync complete!");
            $this->info("Updated/Created: {$updated}");
            $this->info("Skipped: {$skipped}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error('Thread color image sync failed', [
                'folder_url' => $folderUrl,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
}

