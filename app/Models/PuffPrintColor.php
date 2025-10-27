<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PuffPrintColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
    ];
}
