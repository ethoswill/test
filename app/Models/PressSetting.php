<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressSetting extends Model
{
    protected $fillable = [
        'fabric',
        'temperature',
        'pressure',
        'time',
        'number_of_presses',
    ];
}
