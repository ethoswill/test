<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_name',
        'sku',
        'color',
        'size',
        'weight',
        'inventory_quantity',
        'price',
        'cost',
        'barcode',
        'attributes',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'inventory_quantity' => 0,
        'is_active' => true,
    ];

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if variant is in stock.
     */
    public function isInStock(): bool
    {
        return $this->inventory_quantity > 0;
    }

    /**
     * Check if variant is low in stock.
     */
    public function isLowStock(int $threshold = 10): bool
    {
        return $this->inventory_quantity <= $threshold && $this->inventory_quantity > 0;
    }

    /**
     * Check if variant is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->inventory_quantity <= 0;
    }

    /**
     * Get the profit margin for this variant.
     */
    public function getProfitMarginAttribute(): ?float
    {
        if (!$this->cost || $this->cost <= 0) {
            return null;
        }

        return round((($this->price - $this->cost) / $this->cost) * 100, 2);
    }

    /**
     * Scope for active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for variants in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('inventory_quantity', '>', 0);
    }

    /**
     * Scope for low stock variants.
     */
    public function scopeLowStock($query, int $threshold = 10)
    {
        return $query->where('inventory_quantity', '<=', $threshold)
                    ->where('inventory_quantity', '>', 0);
    }
}
