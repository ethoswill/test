<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sock extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'color',
        'size',
        'material',
        'ribbing_height',
        'fabric',
        'images',
        'gallery_images',
        'minimums',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the images as an array from the textarea input.
     */
    public function getImagesAttribute($value)
    {
        // If value is already an array (from JSON cast), return it
        if (is_array($value)) {
            return $value ?: [];
        }
        
        // If value is a JSON string, decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            // If not valid JSON, treat as newline-separated string
            return array_filter(array_map('trim', explode("\n", $value)));
        }
        
        return [];
    }

    /**
     * Set the images attribute - convert string to array for JSON storage.
     */
    public function setImagesAttribute($value)
    {
        if (is_string($value)) {
            // Split by newlines and filter empty lines
            $array = array_filter(array_map('trim', explode("\n", $value)));
            $this->attributes['images'] = json_encode(array_values($array));
        } elseif (is_array($value)) {
            // Filter empty values and encode as JSON
            $this->attributes['images'] = json_encode(array_values(array_filter($value)));
        } else {
            $this->attributes['images'] = json_encode([]);
        }
    }

    /**
     * Get the gallery_images as an array of objects with url and description.
     */
    public function getGalleryImagesAttribute($value)
    {
        // If value is already an array (from JSON cast), return it
        if (is_array($value)) {
            return $value ?: [];
        }
        
        // If value is a JSON string, decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Convert old format (just URLs) to new format (objects with url and description)
                $result = [];
                foreach ($decoded as $item) {
                    if (is_string($item)) {
                        // Old format: just URL string
                        $result[] = ['url' => $item, 'description' => ''];
                    } elseif (is_array($item) && isset($item['url'])) {
                        // New format: object with url and description
                        $result[] = [
                            'url' => $item['url'] ?? '',
                            'description' => $item['description'] ?? ''
                        ];
                    }
                }
                return $result;
            }
            // If not valid JSON, treat as newline-separated string (legacy format)
            $urls = array_filter(array_map('trim', explode("\n", $value)));
            $result = [];
            foreach ($urls as $url) {
                $result[] = ['url' => $url, 'description' => ''];
            }
            return $result;
        }
        
        return [];
    }

    /**
     * Set the gallery_images attribute - store as JSON array of objects.
     */
    public function setGalleryImagesAttribute($value)
    {
        if (is_array($value)) {
            // Filter out empty items and ensure proper structure
            $filtered = [];
            foreach ($value as $item) {
                if (is_array($item) && !empty($item['url'])) {
                    $filtered[] = [
                        'url' => trim($item['url']),
                        'description' => trim($item['description'] ?? '')
                    ];
                }
            }
            $this->attributes['gallery_images'] = json_encode(array_values($filtered));
        } else {
            $this->attributes['gallery_images'] = json_encode([]);
        }
    }

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Scope for active socks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}