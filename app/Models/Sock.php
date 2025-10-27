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
        if (is_string($value)) {
            return array_filter(array_map('trim', explode("\n", $value)));
        }
        return $value ?: [];
    }

    /**
     * Set the images attribute from array to string.
     */
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = implode("\n", array_filter($value));
        } else {
            $this->attributes['images'] = $value;
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