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
        
        // Use API key authentication (simpler than service account)
        $apiKey = env('GOOGLE_API_KEY');
        if (!$apiKey) {
            throw new \Exception('GOOGLE_API_KEY not found in environment variables. Please add it to your .env file');
        }
        
        $this->client->setDeveloperKey($apiKey);
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
                if (count($row) >= 1) {
                    $colorCode = $row[0];
                    $imageUrl = isset($row[1]) ? $row[1] : null;
                    
                    $threadColors[] = [
                        'color_name' => $colorCode,
                        'color_code' => $colorCode,
                        'image_url' => $imageUrl ?: 'https://via.placeholder.com/120x59?text=' . $colorCode,
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
            $response = $this->service->spreadsheets->get($spreadsheetId);
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
