<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'content',
    ];
}
