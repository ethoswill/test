<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleDriveService;
use App\Models\ThreadColor;

class ImportThreadColorsFromDrive extends Command
{
    protected $signature = 'thread-colors:import-from-drive {--limit=20 : Number of thread colors to import}';
    protected $description = 'Import thread colors directly from Google Drive folder files';

    public function handle()
    {
        $this->info('Starting thread colors import from Google Drive...');
        
        try {
            $limit = $this->option('limit');
            $this->info("Processing {$limit} thread colors from Drive folder...");
            
            $googleDriveService = new GoogleDriveService();
            $folderId = '1RDevqNIwqVJixqs4VwJGb3GsakeP5kgl';
            
            // Get all files from Google Drive folder
            $files = $googleDriveService->getFolderContents($folderId);
            
            if (empty($files)) {
                $this->error('No files found in Google Drive folder');
                return 1;
            }
            
            // Filter for image files and limit
            $imageFiles = array_filter($files, function($file) {
                $extension = strtolower(pathinfo($file->getName(), PATHINFO_EXTENSION));
                return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });
            
            $imageFiles = array_slice($imageFiles, 0, $limit);
            $this->info("Found " . count($imageFiles) . " image files to process");
            
            // Clear existing data
            ThreadColor::truncate();
            $this->info('Cleared existing thread colors');
            
            $imported = 0;
            $downloadedCount = 0;
            $errors = [];
            
            foreach ($imageFiles as $index => $file) {
                try {
                    $filename = $file->getName();
                    $threadNumber = pathinfo($filename, PATHINFO_FILENAME);
                    
                    $this->info("Processing {$filename} (" . ($index + 1) . "/{$limit})");
                    
                    // Download and store the image
                    $imagePath = $googleDriveService->downloadAndStoreImage($folderId, $threadNumber);
                    
                    if ($imagePath) {
                        $downloadedCount++;
                        $this->info("  ✓ Downloaded image for {$threadNumber}");
                    } else {
                        // Use placeholder if download fails
                        $imagePath = "https://via.placeholder.com/120x59/cccccc/000000?text={$threadNumber}";
                        $this->warn("  ⚠ Using placeholder for {$threadNumber}");
                    }
                    
                    // Create thread color record
                    ThreadColor::create([
                        'color_name' => $threadNumber,
                        'color_code' => $threadNumber,
                        'image_url' => $imagePath,
                    ]);
                    
                    $imported++;
                    
                    // Small delay to prevent rate limiting
                    usleep(200000); // 0.2 second delay
                    
                } catch (\Exception $e) {
                    $errors[] = "File {$filename}: " . $e->getMessage();
                    $this->error("  ✗ Error with {$filename}: " . $e->getMessage());
                }
            }
            
            $this->info('');
            $this->info("✅ Import completed successfully!");
            $this->info("📊 Imported: {$imported} thread colors");
            $this->info("🖼️ Downloaded: {$downloadedCount} images from Google Drive");
            
            if (!empty($errors)) {
                $this->warn("⚠️ Errors: " . count($errors) . " items had issues");
                foreach ($errors as $error) {
                    $this->warn("  - {$error}");
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
