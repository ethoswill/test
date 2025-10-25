<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'transaction_type',
        'reference_number',
        'description',
        'amount',
        'transaction_date',
        'due_date',
        'status',
        'notes',
        'job_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'due_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    // Helper methods for financial calculations
    public function isMoneyOwedToUs(): bool
    {
        return $this->amount > 0;
    }

    public function isMoneyWeOwe(): bool
    {
        return $this->amount < 0;
    }

    public function getAbsoluteAmount(): float
    {
        return abs($this->amount);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status === 'pending';
    }
}
