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
        
        // Try to load credentials from environment variable first
        $credentialsPath = env('GOOGLE_APPLICATION_CREDENTIALS', storage_path('app/google-credentials.json'));
        
        if (file_exists($credentialsPath)) {
            $this->client->setAuthConfig($credentialsPath);
        } else {
            // Fallback to API key if available
            $apiKey = env('GOOGLE_API_KEY');
            if ($apiKey) {
                $this->client->setDeveloperKey($apiKey);
            } else {
                throw new \Exception('No Google credentials found. Please set up GOOGLE_APPLICATION_CREDENTIALS or GOOGLE_API_KEY');
            }
        }
        
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
