<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'medusa_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'gender',
        'metadata',
        'has_account',
        'last_login_at',
        'synced_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'synced_at' => 'datetime',
        'has_account' => 'boolean',
    ];

    /**
     * Get the customer's full name
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Scope to find by Medusa ID
     */
    public function scopeByMedusaId($query, string $medusaId)
    {
        return $query->where('medusa_id', $medusaId);
    }

    /**
     * Get the payment methods for the customer.
     */
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    /**
     * Get the customer's default payment method.
     */
    public function defaultPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class)->where('is_default', true);
    }

    /**
     * Get the customer's active payment methods.
     */
    public function activePaymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class)->where('is_active', true);
    }

    /**
     * Check if customer has any payment methods on file.
     */
    public function hasPaymentMethods(): bool
    {
        return $this->paymentMethods()->active()->exists();
    }

    /**
     * Check if customer has a default payment method.
     */
    public function hasDefaultPaymentMethod(): bool
    {
        return $this->defaultPaymentMethod()->exists();
    }
}
