<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bottle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'images',
        'material',
        'price',
        'color',
        'size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    /**
     * Get images as array
     */
    public function getImagesAttribute($value): array
    {
        if (empty($value)) {
            return [];
        }

        // Split by newline and filter empty values
        $images = array_filter(array_map('trim', explode("\n", $value)));
        return array_values($images);
    }

    /**
     * Set images from array
     */
    public function setImagesAttribute($value): void
    {
        if (is_array($value)) {
            $value = implode("\n", array_filter(array_map('trim', $value)));
        }

        $this->attributes['images'] = $value;
    }
}
