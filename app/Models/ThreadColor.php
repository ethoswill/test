<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadColor extends Model
{
    protected $fillable = [
        'color_name',
        'color_code',
        'image_url',
    ];
}

