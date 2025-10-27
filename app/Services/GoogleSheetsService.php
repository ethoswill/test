<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Product Database');
        $this->client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $this->client->setAccessType('offline');
        
        // For now, we'll use API key authentication
        // In production, you'd want to use OAuth2 or service account
        $this->client->setDeveloperKey(config('services.google.api_key'));
        
        $this->service = new Sheets($this->client);
    }

    /**
     * Read data from a Google Sheet
     */
    public function readSheet(string $spreadsheetId, string $range = 'A:Z'): array
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues() ?? [];
        } catch (\Exception $e) {
            Log::error('Google Sheets API Error: ' . $e->getMessage());
            throw new \Exception('Failed to read Google Sheet: ' . $e->getMessage());
        }
    }

    /**
     * Parse Google Sheets data into products
     */
    public function parseProductsFromSheet(array $sheetData): array
    {
        if (empty($sheetData)) {
            return [];
        }

        $headers = array_shift($sheetData); // First row is headers
        $products = [];

        foreach ($sheetData as $rowIndex => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            $productData = [];
            
            // Map each column to product field
            foreach ($headers as $columnIndex => $header) {
                $value = $row[$columnIndex] ?? '';
                
                // Map common column names to product fields
                $field = $this->mapColumnToField($header);
                if ($field) {
                    $productData[$field] = trim($value);
                }
            }

            // Only create product if we have a name
            if (!empty($productData['name'])) {
                // Generate Ethos ID if not provided
                if (empty($productData['sku'])) {
                    $productData['sku'] = $this->generateConsistentEthosId($productData['name'], $productData['supplier'] ?? '');
                }
                
                $products[] = $productData;
            }
        }

        return $products;
    }

    /**
     * Map Google Sheets column headers to product fields
     */
    private function mapColumnToField(string $header): ?string
    {
        $header = strtolower(trim($header));
        
        $mapping = [
            // Ethos ID variations (first column)
            'ethos_id' => 'sku',
            'ethos id' => 'sku',
            'ethosid' => 'sku',
            'eid' => 'sku',
            'id' => 'sku',
            'product_id' => 'sku',
            
            // Product name variations
            'name' => 'name',
            'product name' => 'name',
            'product_name' => 'name',
            'title' => 'name',
            'item name' => 'name',
            'item_name' => 'name',
            
            // Supplier variations
            'supplier' => 'supplier',
            'vendor' => 'supplier',
            'brand' => 'supplier',
            'manufacturer' => 'supplier',
            
            // Product type variations
            'type' => 'product_type',
            'product type' => 'product_type',
            'product_type' => 'product_type',
            'category' => 'product_type',
            'style' => 'product_type',
            
            // Website URL variations
            'url' => 'website_url',
            'website' => 'website_url',
            'website url' => 'website_url',
            'website_url' => 'website_url',
            'link' => 'website_url',
            
            // Base color variations
            'color' => 'base_color',
            'base color' => 'base_color',
            'base_color' => 'base_color',
            'primary color' => 'base_color',
            'primary_color' => 'base_color',
            'main color' => 'base_color',
            'main_color' => 'base_color',
            
            // Tone on tone darker variations
            'tone on tone darker' => 'tone_on_tone_darker',
            'darker' => 'tone_on_tone_darker',
            'tone darker' => 'tone_on_tone_darker',
            'tone_darker' => 'tone_on_tone_darker',
            'dark color' => 'tone_on_tone_darker',
            'dark_color' => 'tone_on_tone_darker',
            'shadow' => 'tone_on_tone_darker',
            
            // Tone on tone lighter variations
            'tone on tone lighter' => 'tone_on_tone_lighter',
            'lighter' => 'tone_on_tone_lighter',
            'tone lighter' => 'tone_on_tone_lighter',
            'tone_lighter' => 'tone_on_tone_lighter',
            'light color' => 'tone_on_tone_lighter',
            'light_color' => 'tone_on_tone_lighter',
            'highlight' => 'tone_on_tone_lighter',
            
            // Notes variations
            'notes' => 'notes',
            'description' => 'notes',
            'comments' => 'notes',
            'remarks' => 'notes',
        ];

        return $mapping[$header] ?? null;
    }

    /**
     * Extract spreadsheet ID from Google Sheets URL
     */
    public function extractSpreadsheetId(string $url): ?string
    {
        // Handle various Google Sheets URL formats
        $patterns = [
            '/\/spreadsheets\/d\/([a-zA-Z0-9-_]+)/',
            '/\/d\/([a-zA-Z0-9-_]+)/',
            '/spreadsheets\/d\/([a-zA-Z0-9-_]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Validate Google Sheets URL
     */
    public function isValidGoogleSheetsUrl(string $url): bool
    {
        return $this->extractSpreadsheetId($url) !== null;
    }

    /**
     * Generate a consistent Ethos ID for a product
     */
    private function generateConsistentEthosId(string $productName, string $supplier = ''): string
    {
        // Create a consistent Ethos ID based on product name and supplier
        // This ensures the same product always gets the same Ethos ID
        $hash = md5($productName . $supplier);
        $numericHash = hexdec(substr($hash, 0, 8));
        
        // Convert to 10-digit format starting from 1
        $ethosNumber = ($numericHash % 9999999999) + 1;
        
        return 'EiD' . str_pad($ethosNumber, 10, '0', STR_PAD_LEFT);
    }
}
