<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'company_logo',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($store) {
            $store->status = true;
        });
        
        static::updating(function ($store) {
            // Delete old logo file when logo is changed or removed
            if ($store->isDirty('company_logo') && $store->getOriginal('company_logo')) {
                $oldLogo = $store->getOriginal('company_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }
        });
        
        static::deleting(function ($store) {
            // Delete logo file when store is deleted
            if ($store->company_logo && Storage::disk('public')->exists($store->company_logo)) {
                Storage::disk('public')->delete($store->company_logo);
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'store_product');
    }
    
    public function getLogoUrlAttribute(): string
    {
        if (!$this->company_logo) {
            return '';
        }
        
        return asset('storage/' . $this->company_logo);
    }
}
