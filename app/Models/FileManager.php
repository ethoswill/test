<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    protected $fillable = [
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'store_id',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($fileManager) {
            if ($fileManager->file_path) {
                $fileManager->populateFileInfo();
            }
        });
        
        static::updating(function ($fileManager) {
            if ($fileManager->isDirty('file_path')) {
                $fileManager->populateFileInfo();
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    
    public function getFileUrlAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }
        
        // Ensure we use the correct port for local development
        $url = asset('storage/' . $this->file_path);
        if (str_contains($url, 'localhost') && !str_contains($url, ':8000')) {
            $url = str_replace('localhost', 'localhost:8000', $url);
        }
        
        return $url;
    }
    
    public function isImage(): bool
    {
        return $this->mime_type && str_starts_with($this->mime_type, 'image/');
    }
    
    public function getFileSizeFormattedAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($this->file_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    private function populateFileInfo(): void
    {
        if (!$this->file_path) {
            return;
        }
        
        // Ensure file_path doesn't start with 'storage/' or 'public/'
        $this->file_path = ltrim($this->file_path, 'storage/');
        $this->file_path = ltrim($this->file_path, 'public/');
        
        $fullPath = Storage::disk('public')->path($this->file_path);
        
        if (file_exists($fullPath)) {
            $this->filename = basename($this->file_path);
            $this->original_name = $this->original_name ?: $this->filename;
            $this->file_size = filesize($fullPath);
            $this->mime_type = mime_content_type($fullPath);
        }
    }
}
