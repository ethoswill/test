<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'website',
        'notes',
        'is_active',
        'contact_person',
        'tax_id',
        'w9_file_path',
        'user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(VendorTransaction::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    // Financial calculation methods
    public function getTotalOwedToUs(): float
    {
        return $this->transactions()
            ->where('amount', '>', 0)
            ->where('status', '!=', 'cancelled')
            ->sum('amount');
    }

    public function getTotalWeOwe(): float
    {
        return abs($this->transactions()
            ->where('amount', '<', 0)
            ->where('status', '!=', 'cancelled')
            ->sum('amount'));
    }

    public function getNetBalance(): float
    {
        return $this->getTotalOwedToUs() - $this->getTotalWeOwe();
    }

    public function getPendingInvoices(): float
    {
        return $this->transactions()
            ->where('amount', '>', 0)
            ->where('status', 'pending')
            ->sum('amount');
    }

    public function getOverdueAmount(): float
    {
        return $this->transactions()
            ->where('amount', '>', 0)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->sum('amount');
    }

    public function hasW9(): bool
    {
        return !empty($this->w9_file_path) && file_exists(storage_path('app/public/' . $this->w9_file_path));
    }

    public function getW9Url(): ?string
    {
        return $this->w9_file_path ? asset('storage/' . $this->w9_file_path) : null;
    }
}
