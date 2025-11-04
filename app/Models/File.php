<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'name',
        'file_name',
        'path',
        'disk',
        'size',
        'mime_type',
        'url',
        'description',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the file URL
     */
    public function getFileUrlAttribute(): ?string
    {
        // Always generate fresh URL from path to ensure correct base URL
        if ($this->path && $this->disk) {
            return Storage::disk($this->disk)->url($this->path);
        }

        // Fallback to stored URL if path not available
        if ($this->url) {
            // Fix URL if it's missing port
            if (str_contains($this->url, 'http://localhost/') && !str_contains($this->url, 'localhost:8000')) {
                return str_replace('http://localhost/', 'http://localhost:8000/', $this->url);
            }
            return $this->url;
        }

        return null;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Check if file exists in storage
     */
    public function exists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }
}
