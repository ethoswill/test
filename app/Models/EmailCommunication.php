<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'related_type',
        'related_id',
        'to_email',
        'to_name',
        'from_email',
        'from_name',
        'subject',
        'body',
        'template_name',
        'attachments',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the related model (polymorphic relationship).
     */
    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }

    /**
     * Scope to get communications by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get communications by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent communications.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('sent_at', '>=', now()->subDays($days));
    }

    /**
     * Get the formatted sent date.
     */
    public function getFormattedSentAtAttribute(): string
    {
        return $this->sent_at->format('M j, Y g:i A');
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'sent' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'gray',
        };
    }

    /**
     * Get the status badge text.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'sent' => 'Sent',
            'failed' => 'Failed',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }

    /**
     * Check if the email has attachments.
     */
    public function hasAttachments(): bool
    {
        return !empty($this->attachments);
    }

    /**
     * Get the number of attachments.
     */
    public function getAttachmentCountAttribute(): int
    {
        return count($this->attachments ?? []);
    }
}