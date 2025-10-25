<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function isVendor(): bool
    {
        return $this->vendor !== null;
    }

    // Customer analytics methods
    public function isNewCustomer(): bool
    {
        return $this->created_at >= now()->startOfWeek();
    }

    public function isReturningCustomer(): bool
    {
        return $this->updated_at >= now()->startOfWeek() && 
               $this->created_at < now()->startOfWeek();
    }

    public function isActiveCustomer(): bool
    {
        return $this->updated_at >= now()->subWeek();
    }

    public function getCustomerType(): string
    {
        if ($this->isNewCustomer()) return 'New';
        if ($this->isReturningCustomer()) return 'Returning';
        if ($this->isActiveCustomer()) return 'Active';
        return 'Inactive';
    }

    public function getDaysSinceSignup(): int
    {
        return now()->diffInDays($this->created_at);
    }

    public function getDaysSinceLastActivity(): int
    {
        return now()->diffInDays($this->updated_at);
    }
}
