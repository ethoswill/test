<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'eid',
        'order_number',
        'customer_name',
        'order_date',
        'status',
        'product_id',
        'quantity',
        'notes',
        // Sock Details
        'thread_color',
        'thread_colors',
        'grip_design_file',
        'packaging_design_file',
        'logo_style',
        'embroidered_logo_thread_colors',
        'grip_color',
        // Sample workflow
        'sample_image_1',
        'sample_image_2',
        'sample_approved',
        'revision_notes',
        // Production workflow
        'ship_date_eta',
        'is_shipped',
        'tracking_number',
        'sock_images',
    ];

    protected $casts = [
        'order_date' => 'date',
        'ship_date_eta' => 'date',
        'is_shipped' => 'boolean',
        'sample_approved' => 'boolean',
        'sock_images' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'Order Submitted' => 'gray',
            'Sock Details Submitted' => 'blue',
            'Sample In Production' => 'warning',
            'Sample Pending Approval' => 'orange',
            'Sample Approved' => 'success',
            'Sample Revision Requested' => 'danger',
            'In Bulk Production' => 'info',
            'Shipped' => 'success',
            'Delivered' => 'success',
            default => 'gray',
        };
    }

    public function getStatusIcon(): string
    {
        return match($this->status) {
            'Order Submitted' => 'heroicon-o-document-text',
            'Sock Details Submitted' => 'heroicon-o-pencil-square',
            'Sample In Production' => 'heroicon-o-cog-6-tooth',
            'Sample Pending Approval' => 'heroicon-o-clock',
            'Sample Approved' => 'heroicon-o-check-circle',
            'Sample Revision Requested' => 'heroicon-o-exclamation-triangle',
            'In Bulk Production' => 'heroicon-o-cube',
            'Shipped' => 'heroicon-o-truck',
            'Delivered' => 'heroicon-o-check-circle',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    public static function generateEID(): string
    {
        $lastOrder = static::orderBy('id', 'desc')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        return 'EID' . str_pad($nextId, 14, '0', STR_PAD_LEFT);
    }
}
