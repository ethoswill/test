<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'invoice_number',
        'description',
        'amount',
        'invoice_date',
        'due_date',
        'status',
        'notes',
        'job_reference',
        'line_items',
        'tax_rate',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'line_items' => 'array',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status === 'sent';
    }

    public function getFormattedLineItems(): array
    {
        return $this->line_items ?? [];
    }

    public function calculateTotal(): float
    {
        $subtotal = $this->amount;
        $taxAmount = $subtotal * ($this->tax_rate / 100);
        $totalAmount = $subtotal + $taxAmount;
        
        // Update the model attributes without mutating the original
        $this->setAttribute('tax_amount', $taxAmount);
        $this->setAttribute('total_amount', $totalAmount);
        
        return $totalAmount;
    }

    public function calculateSubtotal(): float
    {
        if (empty($this->line_items)) {
            return $this->amount ?? 0;
        }

        $subtotal = 0;
        foreach ($this->line_items as $item) {
            $quantity = $item['quantity'] ?? 0;
            $unitPrice = $item['unit_price'] ?? 0;
            $subtotal += $quantity * $unitPrice;
        }

        return $subtotal;
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'sent' => 'warning',
            'paid' => 'success',
            'overdue' => 'danger',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
