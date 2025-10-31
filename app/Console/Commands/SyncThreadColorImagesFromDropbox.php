<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThreadColor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncThreadColorImagesFromDropbox extends Command
{
    protected $signature = 'thread-colors:sync-dropbox {folderUrl}';
    
    protected $description = 'Sync thread color images from Dropbox folder by scraping the public page';

    public function handle()
    {
        $folderUrl = $this->argument('folderUrl');
        
        $this->info("Fetching images from Dropbox folder...");
        
        try {
            // Fetch the Dropbox folder page
            $response = Http::timeout(30)->get($folderUrl);
            
            if (!$response->successful()) {
                $this->error("Failed to fetch Dropbox folder");
                return 1;
            }
            
            $html = $response->body();
            
            // Extract file information from the HTML
            // Dropbox shows files in a data structure we can parse
            preg_match_all('/href="([^"]+download)"/', $html, $downloadMatches);
            preg_match_all('/class="[^"]*filename[^"]*">([^<]+)</', $html, $filenameMatches);
            
            // Better approach: look for the JSON data structure
            preg_match('/window\.__pageData\s*=\s*({.+?});/', $html, $jsonMatch);
            
            if (isset($jsonMatch[1])) {
                $data = json_decode($jsonMatch[1], true);
                
                if ($data && isset($data['listing']['entries'])) {
                    $files = $data['listing']['entries'];
                    return $this->processFiles($files);
                }
            }
            
            // Fallback: try to extract files manually
            $this->warn("Could not parse Dropbox page structure, trying fallback method...");
            
            // Look for direct download links
            preg_match_all('/href="(https:\/\/[^"]*dropbox\.com[^"]*download[^"]*)"/', $html, $links);
            
            if (empty($links[1])) {
                $this->error("Could not extract file links from Dropbox folder");
                $this->info("You may need to use the Dropbox API with a valid access token");
                return 1;
            }
            
            $this->info("Found " . count($links[1]) . " potential file links");
            $this->warn("Manual extraction method is limited. Please provide a Dropbox API token for full functionality.");
            
            return 1;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            Log::error('Thread color image sync from Dropbox failed', [
                'folder_url' => $folderUrl,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
    
    protected function processFiles(array $files)
    {
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($files as $file) {
            // Skip directories
            if (!isset($file['.tag']) || $file['.tag'] !== 'file') {
                continue;
            }
            
            $filename = $file['name'] ?? '';
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            // Only process image files
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                continue;
            }
            
            // Extract thread color code from filename
            preg_match('/(\d+)/', pathinfo($filename, PATHINFO_FILENAME), $matches);
            
            if (empty($matches[1])) {
                $this->warn("Could not extract thread color code from: {$filename}");
                $skipped++;
                continue;
            }
            
            $colorCode = $matches[1];
            
            // Get the download URL
            $downloadUrl = $file['link'] ?? $file['download_url'] ?? null;
            
            if (!$downloadUrl) {
                $this->error("No download URL for: {$filename}");
                $errors++;
                continue;
            }
            
            // Find and update the thread color
            $threadColor = ThreadColor::where('color_code', $colorCode)
                ->orWhere('color_name', $colorCode)
                ->first();
            
            if ($threadColor) {
                if ($threadColor->image_url !== $downloadUrl) {
                    $threadColor->image_url = $downloadUrl;
                    $threadColor->save();
                    $this->info("Updated: {$colorCode} - {$filename}");
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                ThreadColor::create([
                    'color_name' => $colorCode,
                    'color_code' => $colorCode,
                    'image_url' => $downloadUrl,
                ]);
                $this->info("Created: {$colorCode} - {$filename}");
                $updated++;
            }
        }
        
        $this->newLine();
        $this->info("Sync complete!");
        $this->info("Updated/Created: {$updated}");
        $this->info("Skipped: {$skipped}");
        $this->warn("Errors: {$errors}");
        
        return 0;
    }
}
