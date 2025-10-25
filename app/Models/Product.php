<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'supplier_id',
        'supplier_code',
        'website_url',
        'hs_code',
        'parent_product',
        'status',
        'product_type',
        'store_id',
        'vendor_id',
        'minimums',
        'starting_from_price',
        'fabric',
        'how_it_fits',
        'care_instructions',
        'lead_times',
        'available_sizes',
        'customization_methods',
        'model_size',
        'ethos_cost_price',
        'customer_b2b_price',
        'customer_dtc_price',
        'franchisee_price',
        'minimum_order_quantity',
        'split_across_variants',
        // Price fields
        'cost_price',
        'retail_price',
        // Sock customization fields
        'thread_color',
        'thread_colors',
        'logo_style',
        'embroidered_logo_thread_colors',
        'grip_color',
    ];

    protected $casts = [
        'media' => 'array',
        'split_across_variants' => 'boolean',
    ];

    protected $hidden = [
        'media',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_product');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
