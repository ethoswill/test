<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'vendor_id',
        'client_name',
        'client_email',
        'client_address',
        'client_phone',
        'description',
        'line_items',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'po_date',
        'delivery_date',
        'status',
        'notes',
        'terms_conditions',
        'delivery_address',
        'contact_person',
    ];

    protected $casts = [
        'line_items' => 'array',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'po_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function calculateSubtotal(): float
    {
        if (empty($this->line_items)) {
            return $this->subtotal ?? 0;
        }

        $subtotal = 0;
        foreach ($this->line_items as $item) {
            $quantity = $item['quantity'] ?? 0;
            $unitPrice = $item['unit_price'] ?? 0;
            $subtotal += $quantity * $unitPrice;
        }

        return $subtotal;
    }

    public function calculateTotal(): float
    {
        $subtotal = $this->calculateSubtotal();
        $taxAmount = $subtotal * ($this->tax_rate / 100);
        $totalAmount = $subtotal + $taxAmount;
        
        $this->setAttribute('subtotal', $subtotal);
        $this->setAttribute('tax_amount', $taxAmount);
        $this->setAttribute('total_amount', $totalAmount);
        
        return $totalAmount;
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'sent' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'success',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function generatePONumber(): string
    {
        $prefix = 'PO';
        $year = date('Y');
        $month = date('m');
        
        $lastPO = static::where('po_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('po_number', 'desc')
            ->first();
        
        if ($lastPO) {
            $lastNumber = (int) substr($lastPO->po_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
