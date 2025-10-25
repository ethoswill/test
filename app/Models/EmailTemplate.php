<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'type',
        'is_active',
        'variables',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
    ];

    /**
     * Scope to get only active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get templates by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the processed subject with variables replaced.
     */
    public function getProcessedSubject(array $variables = []): string
    {
        return $this->processTemplate($this->subject, $variables);
    }

    /**
     * Get the processed body with variables replaced.
     */
    public function getProcessedBody(array $variables = []): string
    {
        return $this->processTemplate($this->body, $variables);
    }

    /**
     * Process template with variable replacement.
     */
    protected function processTemplate(string $template, array $variables = []): string
    {
        $processed = $template;
        
        foreach ($variables as $key => $value) {
            $processed = str_replace("{{$key}}", $value, $processed);
        }
        
        return $processed;
    }

    /**
     * Get available variables for this template.
     */
    public function getAvailableVariables(): array
    {
        return $this->variables ?? [];
    }

    /**
     * Get default variables for purchase order templates.
     */
    public static function getPurchaseOrderVariables(): array
    {
        return [
            'po_number' => 'Purchase Order Number',
            'client_name' => 'Client Name',
            'client_email' => 'Client Email',
            'total_amount' => 'Total Amount',
            'delivery_date' => 'Delivery Date',
            'vendor_name' => 'Vendor Name',
            'company_name' => 'Your Company Name',
            'current_date' => 'Current Date',
        ];
    }
}