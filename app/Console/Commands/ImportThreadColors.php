<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;
use App\Services\GoogleDriveService;
use App\Models\ThreadColor;

class ImportThreadColors extends Command
{
    protected $signature = 'thread-colors:import {--limit=10 : Number of thread colors to import}';
    protected $description = 'Import thread colors from Google Sheets and download images from Google Drive';

    public function handle()
    {
        $this->info('Starting thread colors import...');
        
        try {
            $limit = $this->option('limit');
            $this->info("Processing {$limit} thread colors...");
            
            $googleSheetsService = new GoogleSheetsService();
            $googleDriveService = new GoogleDriveService();
            
            // Get thread colors from Google Sheets
            $spreadsheetId = '1gTHgdksxGx7CThTbAENPJ44ndhCJBJPoEn0l1_68QK8';
            $threadColors = $googleSheetsService->getThreadColorsFromSheet($spreadsheetId, 'Madeira Swatches!A:B');
            
            if (empty($threadColors)) {
                $this->error('No thread colors found in Google Sheets');
                return 1;
            }
            
            // Limit processing
            $threadColors = array_slice($threadColors, 0, $limit);
            $this->info("Found " . count($threadColors) . " thread colors to process");
            
            // Clear existing data
            ThreadColor::truncate();
            $this->info('Cleared existing thread colors');
            
            $imported = 0;
            $downloadedCount = 0;
            $errors = [];
            
            $folderId = '1RDevqNIwqVJixqs4VwJGb3GsakeP5kgl';
            
            foreach ($threadColors as $index => $threadColor) {
                try {
                    $this->info("Processing thread color {$threadColor['color_code']} (" . ($index + 1) . "/{$limit})");
                    
                    // Try to get image from Google Drive
                    $imagePath = $googleDriveService->downloadAndStoreImage($folderId, $threadColor['color_code']);
                    
                    if ($imagePath) {
                        $threadColor['image_url'] = $imagePath;
                        $downloadedCount++;
                        $this->info("  ✓ Downloaded image for {$threadColor['color_code']}");
                    } else {
                        // Use placeholder if no image found
                        $threadColor['image_url'] = "https://via.placeholder.com/120x59/cccccc/000000?text={$threadColor['color_code']}";
                        $this->warn("  ⚠ Using placeholder for {$threadColor['color_code']}");
                    }
                    
                    ThreadColor::create($threadColor);
                    $imported++;
                    
                    // Small delay to prevent rate limiting
                    usleep(200000); // 0.2 second delay
                    
                } catch (\Exception $e) {
                    $errors[] = "Thread {$threadColor['color_code']}: " . $e->getMessage();
                    $this->error("  ✗ Error with {$threadColor['color_code']}: " . $e->getMessage());
                    
                    // Still create the thread color with placeholder
                    $threadColor['image_url'] = "https://via.placeholder.com/120x59/cccccc/000000?text={$threadColor['color_code']}";
                    ThreadColor::create($threadColor);
                    $imported++;
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
