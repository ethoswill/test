<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ThreadColor;
use Illuminate\Support\Facades\Log;

class SyncThreadColorsFromGoogleSheet extends Command
{
    protected $signature = 'thread-colors:sync-sheet {sheetUrl}';
    
    protected $description = 'Sync thread color images from Google Sheets with IMAGE formulas - optimized single API call';

    public function handle()
    {
        $sheetUrl = $this->argument('sheetUrl');
        
        $this->info("Fetching data from Google Sheets...");
        
        try {
            // Extract spreadsheet ID
            if (preg_match('/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/', $sheetUrl, $matches)) {
                $spreadsheetId = $matches[1];
            } else {
                $this->error('Invalid Google Sheets URL');
                return 1;
            }
            
            $this->info("Spreadsheet ID: {$spreadsheetId}");
            
            // Get Google API client
            $client = new \Google\Client();
            $client->setApplicationName('Thread Colors Importer');
            $apiKey = env('GOOGLE_API_KEY');
            if (!$apiKey) {
                $this->error('GOOGLE_API_KEY not found in .env file');
                return 1;
            }
            $client->setDeveloperKey($apiKey);
            $sheetsService = new \Google\Service\Sheets($client);
            
            // Single optimized API call to get all data with formulas
            $this->info("Fetching all data in one request...");
            $spreadsheet = $sheetsService->spreadsheets->get($spreadsheetId, [
                'includeGridData' => true,
                'ranges' => ['Madeira Swatches!A:B']
            ]);
            
            $sheet = $spreadsheet->getSheets()[0];
            $data = $sheet->getData()[0];
            
            if (!isset($data['rowData'])) {
                $this->error('No data found in sheet');
                return 1;
            }
            
            $updated = 0;
            $skipped = 0;
            $created = 0;
            $totalRows = count($data['rowData']);
            
            $this->info("Found {$totalRows} rows");
            
            // Start from row 1 (skip header)
            for ($i = 1; $i < $totalRows; $i++) {
                $rowData = $data['rowData'][$i];
                
                // Get color code from column A (index 0)
                $colorCode = $this->extractCellValue($rowData, 0);
                
                if (empty($colorCode)) {
                    continue;
                }
                
                // Get image URL from column B (index 1) 
                $imageUrl = $this->extractImageUrlFromFormula($rowData, 1);
                
                if (!$imageUrl) {
                    $this->warn("No image URL for: {$colorCode}");
                    $skipped++;
                    continue;
                }
                
                // Find or create thread color
                $threadColor = ThreadColor::where('color_code', $colorCode)
                    ->orWhere('color_name', $colorCode)
                    ->first();
                
                if ($threadColor) {
                    if ($threadColor->image_url !== $imageUrl) {
                        $threadColor->image_url = $imageUrl;
                        $threadColor->save();
                        $this->info("✓ Updated: {$colorCode}");
                        $updated++;
                    } else {
                        $skipped++;
                    }
                } else {
                    ThreadColor::create([
                        'color_name' => $colorCode,
                        'color_code' => $colorCode,
                        'image_url' => $imageUrl,
                    ]);
                    $this->info("✓ Created: {$colorCode}");
                    $created++;
                }
            }
            
            $this->newLine();
            $this->info("=== Sync Complete! ===");
            $this->info("Created: {$created}");
            $this->info("Updated: {$updated}");
            $this->info("Skipped: {$skipped}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            if (strpos($e->getMessage(), 'PERMISSION_DENIED') !== false) {
                $this->error("\nMake sure the Google Sheet is shared publicly with 'Anyone with the link can view'");
            }
            Log::error('Thread color sync from Google Sheet failed', [
                'sheet_url' => $sheetUrl,
                'error' => $e->getMessage()
            ]);
            return 1;
        }
    }
    
    protected function extractCellValue($rowData, $columnIndex)
    {
        if (!isset($rowData['values'][$columnIndex])) {
            return null;
        }
        
        $cell = $rowData['values'][$columnIndex];
        
        // Try to get the formatted value first
        if (isset($cell['formattedValue'])) {
            return $cell['formattedValue'];
        }
        
        // Fall back to the raw value
        if (isset($cell['userEnteredValue']['stringValue'])) {
            return $cell['userEnteredValue']['stringValue'];
        }
        
        if (isset($cell['userEnteredValue']['numberValue'])) {
            return (string)$cell['userEnteredValue']['numberValue'];
        }
        
        return null;
    }
    
    protected function extractImageUrlFromFormula($rowData, $columnIndex)
    {
        if (!isset($rowData['values'][$columnIndex])) {
            return null;
        }
        
        $cell = $rowData['values'][$columnIndex];
        
        // Check for IMAGE formula
        if (isset($cell['userEnteredValue']['formulaValue'])) {
            $formula = $cell['userEnteredValue']['formulaValue'];
            
            // Extract URL from IMAGE formula
            // Format: IMAGE("URL", 2, 100, 100)
            if (preg_match('/IMAGE\("([^"]+)"/', $formula, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
}
