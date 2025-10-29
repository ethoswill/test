<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DropboxService
{
    private $accessToken;

    public function __construct()
    {
        $this->accessToken = env('DROPBOX_ACCESS_TOKEN');
        if (!$this->accessToken) {
            throw new \Exception('DROPBOX_ACCESS_TOKEN not found in environment variables');
        }
    }

    public function getSharedFolderContents($sharedFolderUrl)
    {
        try {
            // Extract folder ID from shared URL
            $folderId = $this->extractFolderIdFromUrl($sharedFolderUrl);
            
            if (!$folderId) {
                throw new \Exception('Invalid Dropbox shared folder URL');
            }

            // List files in the shared folder
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/files/list_folder', [
                'path' => $folderId,
                'recursive' => false,
                'include_media_info' => false,
                'include_deleted' => false,
                'include_has_explicit_shared_members' => false,
                'include_mounted_folders' => true,
                'include_non_downloadable_files' => false
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['entries'] ?? [];
            } else {
                throw new \Exception('Failed to list Dropbox folder: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Dropbox API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getImageUrlForThreadColor($sharedFolderUrl, $threadColorCode)
    {
        try {
            $files = $this->getSharedFolderContents($sharedFolderUrl);
            
            // Look for files that match the thread color code
            $matchingFiles = array_filter($files, function($file) use ($threadColorCode) {
                if ($file['.tag'] !== 'file') return false;
                
                $filename = pathinfo($file['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                // Check if filename matches thread color code and is an image
                return ($filename === $threadColorCode || $filename === (string)$threadColorCode) 
                    && in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            });

            if (!empty($matchingFiles)) {
                $file = reset($matchingFiles);
                return $this->getTemporaryLink($file['path_lower']);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error getting image for thread color {$threadColorCode}: " . $e->getMessage());
            return null;
        }
    }

    public function getTemporaryLink($filePath)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post('https://api.dropboxapi.com/2/files/get_temporary_link', [
                'path' => $filePath
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['link'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting temporary link: ' . $e->getMessage());
            return null;
        }
    }

    public function downloadAndStoreImage($sharedFolderUrl, $threadColorCode)
    {
        try {
            $imageUrl = $this->getImageUrlForThreadColor($sharedFolderUrl, $threadColorCode);
            
            if (!$imageUrl) {
                return null;
            }

            // Download the image
            $imageContent = file_get_contents($imageUrl);
            if (!$imageContent) {
                return null;
            }

            // Determine file extension
            $extension = 'jpg'; // default
            $headers = get_headers($imageUrl, 1);
            if (isset($headers['Content-Type'])) {
                $contentType = is_array($headers['Content-Type']) ? end($headers['Content-Type']) : $headers['Content-Type'];
                switch ($contentType) {
                    case 'image/png':
                        $extension = 'png';
                        break;
                    case 'image/webp':
                        $extension = 'webp';
                        break;
                    case 'image/gif':
                        $extension = 'gif';
                        break;
                }
            }

            // Store the image locally
            $filename = $threadColorCode . '.' . $extension;
            $path = 'thread-colors/' . $filename;
            
            Storage::disk('public')->put($path, $imageContent);
            
            return $path;
        } catch (\Exception $e) {
            Log::error("Error downloading image for thread color {$threadColorCode}: " . $e->getMessage());
            return null;
        }
    }

    private function extractFolderIdFromUrl($url)
    {
        // Dropbox shared folder URLs can be in different formats
        // Examples:
        // https://www.dropbox.com/scl/fi/abc123/folder-name?dl=0&st=xyz
        // https://www.dropbox.com/sh/abc123/xyz
        // https://www.dropbox.com/scl/fo/abc123/folder-name?rlkey=xyz&st=abc&dl=0
        
        if (preg_match('/dropbox\.com\/sh\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return '/' . $matches[1];
        }
        
        if (preg_match('/dropbox\.com\/scl\/fi\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return '/' . $matches[1];
        }
        
        if (preg_match('/dropbox\.com\/scl\/fo\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return '/' . $matches[1];
        }
        
        return null;
    }

    public function testConnection($sharedFolderUrl)
    {
        try {
            $files = $this->getSharedFolderContents($sharedFolderUrl);
            return [
                'success' => true,
                'fileCount' => count($files),
                'files' => array_map(function($file) {
                    return [
                        'name' => $file['name'],
                        'size' => $file['size'] ?? 0,
                        'type' => $file['.tag']
                    ];
                }, array_slice($files, 0, 10)) // Show first 10 files
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
