<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginInfo extends Model
{
    protected $fillable = [
        'website_name',
        'url',
        'username',
        'password',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
}
