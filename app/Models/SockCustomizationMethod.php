<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SockCustomizationMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'images',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
