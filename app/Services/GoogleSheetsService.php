<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    private $client;
    private $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Thread Colors Importer');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAuthConfig(storage_path('app/google-credentials.json'));
        $this->service = new Sheets($this->client);
    }

    public function getThreadColorsFromSheet($spreadsheetId, $range = 'Madeira Swatches!A:B')
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                return [];
            }

            $threadColors = [];
            $headers = array_shift($values); // Remove header row

            foreach ($values as $row) {
                if (count($row) >= 2) {
                    $threadColors[] = [
                        'color_name' => $row[0],
                        'color_code' => $row[0],
                        'image_url' => $row[1] ?? null,
                    ];
                }
            }

            return $threadColors;
        } catch (\Exception $e) {
            Log::error('Google Sheets API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getThreadColorsWithImagesFromSheet($spreadsheetId, $range = 'Madeira Swatches!A:B')
    {
        try {
            // First, get the values
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                return [];
            }

            // Get the spreadsheet to access images
            $spreadsheet = $this->service->spreadsheets->get($spreadsheetId);
            $sheet = $spreadsheet->getSheets()[0];
            $sheetId = $sheet->getProperties()->getSheetId();

            $threadColors = [];
            $headers = array_shift($values); // Remove header row

            foreach ($values as $index => $row) {
                if (count($row) >= 1) {
                    $colorCode = $row[0];
                    
                    // Try to get image from the sheet
                    $imageUrl = $this->getImageFromSheet($spreadsheetId, $sheetId, $index + 2); // +2 because we removed header and arrays are 0-indexed
                    
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

    private function getImageFromSheet($spreadsheetId, $sheetId, $rowIndex)
    {
        try {
            // This is a simplified approach - in reality, getting images from Google Sheets
            // requires more complex handling of embedded objects
            // For now, we'll return null and handle this differently
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting image from sheet: ' . $e->getMessage());
            return null;
        }
    }
}
