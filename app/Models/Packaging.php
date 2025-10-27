<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    use HasFactory;

    protected $table = 'packaging';

    protected $fillable = [
        'name',
        'description',
        'type',
        'images',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}