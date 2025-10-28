<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SockCustomizationMethodContent extends Model
{
    protected $table = 'sock_customization_method_content';

    protected $fillable = [
        'method_name',
        'notes',
        'image_urls',
    ];

    protected $casts = [
        'image_urls' => 'array',
    ];
}
