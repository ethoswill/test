<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'provider',
        'provider_id',
        'card_brand',
        'card_last_four',
        'card_exp_month',
        'card_exp_year',
        'card_holder_name',
        'is_default',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the customer that owns the payment method.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the masked card number for display.
     */
    public function getMaskedCardNumberAttribute(): string
    {
        if (!$this->card_last_four) {
            return '****';
        }

        return '**** **** **** ' . $this->card_last_four;
    }

    /**
     * Get the formatted expiration date.
     */
    public function getFormattedExpirationAttribute(): string
    {
        if (!$this->card_exp_month || !$this->card_exp_year) {
            return 'N/A';
        }

        return $this->card_exp_month . '/' . substr($this->card_exp_year, -2);
    }

    /**
     * Get the card brand with proper capitalization.
     */
    public function getFormattedBrandAttribute(): string
    {
        if (!$this->card_brand) {
            return 'Unknown';
        }

        return ucfirst(strtolower($this->card_brand));
    }

    /**
     * Check if the card is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->card_exp_month || !$this->card_exp_year) {
            return false;
        }

        $expirationDate = \Carbon\Carbon::createFromDate($this->card_exp_year, $this->card_exp_month, 1)->endOfMonth();
        
        return $expirationDate->isPast();
    }

    /**
     * Check if the card expires soon (within 3 months).
     */
    public function getExpiresSoonAttribute(): bool
    {
        if (!$this->card_exp_month || !$this->card_exp_year) {
            return false;
        }

        $expirationDate = \Carbon\Carbon::createFromDate($this->card_exp_year, $this->card_exp_month, 1)->endOfMonth();
        $threeMonthsFromNow = now()->addMonths(3);
        
        return $expirationDate->isBefore($threeMonthsFromNow);
    }

    /**
     * Scope to get only active payment methods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only default payment methods.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope to get only card payment methods.
     */
    public function scopeCards($query)
    {
        return $query->where('type', 'card');
    }
}