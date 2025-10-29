<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Drive;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoogleSheetsService
{
    private $client;
    private $sheetsService;
    private $driveService;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Thread Colors Importer');
        
        // Use API key authentication (simpler than service account)
        $apiKey = env('GOOGLE_API_KEY');
        if (!$apiKey) {
            throw new \Exception('GOOGLE_API_KEY not found in environment variables. Please add it to your .env file');
        }
        
        $this->client->setDeveloperKey($apiKey);
        $this->sheetsService = new Sheets($this->client);
        $this->driveService = new Drive($this->client);
    }

    public function getThreadColorsWithImages($spreadsheetId, $range = 'Madeira Swatches!A:B')
    {
        try {
            // First, get the spreadsheet data
            $response = $this->sheetsService->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                return [];
            }

            // Get the spreadsheet metadata to access images
            $spreadsheet = $this->sheetsService->spreadsheets->get($spreadsheetId);
            $sheet = $spreadsheet->getSheets()[0];
            $sheetId = $sheet->getProperties()->getSheetId();

            $threadColors = [];
            $headers = array_shift($values); // Remove header row

            foreach ($values as $index => $row) {
                if (count($row) >= 1) {
                    $colorCode = $row[0];
                    
                    // Try to get the image from the sheet
                    $imageUrl = $this->getImageFromSheet($spreadsheetId, $sheetId, $index + 2);
                    
                    $threadColors[] = [
                        'color_name' => $colorCode,
                        'color_code' => $colorCode,
                        'image_url' => $imageUrl ?: 'https://via.placeholder.com/120x59/cccccc/000000?text=' . $colorCode,
                    ];
                }
            }

            return $threadColors;
        } catch (\Exception $e) {
            Log::error('Google Sheets API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getImageFromSheet($spreadsheetId, $sheetId, $rowIndex)
    {
        try {
            // Get the spreadsheet with images
            $spreadsheet = $this->sheetsService->spreadsheets->get($spreadsheetId, [
                'includeGridData' => true,
                'ranges' => ["Madeira Swatches!B{$rowIndex}:B{$rowIndex}"]
            ]);

            $sheet = $spreadsheet->getSheets()[0];
            $gridData = $sheet->getData()[0];
            
            if (isset($gridData['rowData'][0]['values'][0])) {
                $cellData = $gridData['rowData'][0]['values'][0];
                
                // Check if cell has an image
                if (isset($cellData['userEnteredValue']['formulaValue'])) {
                    $formula = $cellData['userEnteredValue']['formulaValue'];
                    
                    // Look for IMAGE formula
                    if (strpos($formula, 'IMAGE(') !== false) {
                        // Extract URL from IMAGE formula
                        preg_match('/IMAGE\("([^"]+)"/', $formula, $matches);
                        if (isset($matches[1])) {
                            return $matches[1];
                        }
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting image from sheet: ' . $e->getMessage());
            return null;
        }
    }

    public function downloadAndStoreImages($spreadsheetId, $range = 'Madeira Swatches!A:B', $limit = 50)
    {
        try {
            // Increase execution time limit
            set_time_limit(300); // 5 minutes
            
            $threadColors = $this->getThreadColorsFromSheet($spreadsheetId, $range);
            $downloadedCount = 0;
            $processedCount = 0;

            foreach ($threadColors as $threadColor) {
                if ($processedCount >= $limit) {
                    break; // Stop after processing limit
                }
                
                try {
                    // Try to get real image URL for this specific thread color
                    $realImageUrl = $this->getImageForThreadColor($spreadsheetId, $threadColor['color_code']);
                    
                    if ($realImageUrl && strpos($realImageUrl, 'placeholder') === false) {
                        // Download and store the image
                        $imageContent = @file_get_contents($realImageUrl);
                        if ($imageContent) {
                            $filename = $threadColor['color_code'] . '.jpg';
                            $path = 'thread-colors/' . $filename;
                            
                            Storage::disk('public')->put($path, $imageContent);
                            
                            // Update the thread color with local path
                            $threadColor['image_url'] = $path;
                            $downloadedCount++;
                        }
                    }
                    
                    $processedCount++;
                    
                    // Add small delay to prevent rate limiting
                    usleep(100000); // 0.1 second delay
                    
                } catch (\Exception $e) {
                    Log::error("Error processing thread color {$threadColor['color_code']}: " . $e->getMessage());
                    continue;
                }
            }

            return [
                'threadColors' => $threadColors,
                'downloadedCount' => $downloadedCount,
                'processedCount' => $processedCount
            ];
        } catch (\Exception $e) {
            Log::error('Error downloading images: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getImageForThreadColor($spreadsheetId, $colorCode)
    {
        try {
            // Get a smaller range to find the specific thread color
            $response = $this->sheetsService->spreadsheets_values->get($spreadsheetId, 'Madeira Swatches!A:B');
            $values = $response->getValues();
            
            if (empty($values)) {
                return null;
            }
            
            // Find the row with this color code
            $targetRow = null;
            foreach ($values as $index => $row) {
                if (isset($row[0]) && $row[0] == $colorCode) {
                    $targetRow = $index + 1; // +1 because arrays are 0-indexed but sheets are 1-indexed
                    break;
                }
            }
            
            if (!$targetRow) {
                return null;
            }
            
            // Get the spreadsheet with grid data for this specific row
            $spreadsheet = $this->sheetsService->spreadsheets->get($spreadsheetId, [
                'includeGridData' => true,
                'ranges' => ["Madeira Swatches!B{$targetRow}:B{$targetRow}"]
            ]);

            $sheet = $spreadsheet->getSheets()[0];
            $gridData = $sheet->getData()[0];
            
            if (isset($gridData['rowData'][0]['values'][0])) {
                $cellData = $gridData['rowData'][0]['values'][0];
                
                // Check if cell has an image formula
                if (isset($cellData['userEnteredValue']['formulaValue'])) {
                    $formula = $cellData['userEnteredValue']['formulaValue'];
                    
                    // Look for IMAGE formula
                    if (strpos($formula, 'IMAGE(') !== false) {
                        // Extract URL from IMAGE formula
                        preg_match('/IMAGE\("([^"]+)"/', $formula, $matches);
                        if (isset($matches[1])) {
                            return $matches[1];
                        }
                    }
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error getting image for thread color {$colorCode}: " . $e->getMessage());
            return null;
        }
    }

    public function getThreadColorsFromSheet($spreadsheetId, $range = 'Madeira Swatches!A:B')
    {
        try {
            $response = $this->sheetsService->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                return [];
            }

            $threadColors = [];
            $headers = array_shift($values); // Remove header row

            foreach ($values as $row) {
                if (count($row) >= 1) {
                    $colorCode = $row[0];
                    
                    // For now, we'll use placeholder images since Google Sheets API doesn't provide direct image URLs
                    // The images in Google Sheets are embedded and not accessible via API
                    $imageUrl = 'https://via.placeholder.com/120x59/cccccc/000000?text=' . $colorCode;
                    
                    $threadColors[] = [
                        'color_name' => $colorCode,
                        'color_code' => $colorCode,
                        'image_url' => $imageUrl,
                    ];
                }
            }

            return $threadColors;
        } catch (\Exception $e) {
            Log::error('Google Sheets API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function testConnection($spreadsheetId)
    {
        try {
            $response = $this->sheetsService->spreadsheets->get($spreadsheetId);
            return [
                'success' => true,
                'title' => $response->getProperties()->getTitle(),
                'sheets' => array_map(function($sheet) {
                    return $sheet->getProperties()->getTitle();
                }, $response->getSheets())
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
