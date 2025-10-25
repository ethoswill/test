<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeggingsDesign extends Model
{
    protected $fillable = [
        'designer_name',
        'design_title',
        'design_description',
        'design_category',
        'design_images',
        'target_size_range',
        'waist_rise',
        'inseam_length',
        'fit_type',
        'special_fit_notes',
        'fabric_type',
        'fabric_weight',
        'stretch_percentage',
        'waistband_type',
        'construction_details',
        'color_options',
        'submission_status',
        'additional_notes',
        'contact_email',
        'phone_number',
        'target_launch_date',
        'estimated_price',
    ];

    protected $casts = [
        'design_images' => 'array',
        'color_options' => 'array',
        'target_launch_date' => 'date',
        'estimated_price' => 'decimal:2',
    ];

    public function getStatusColor(): string
    {
        return match($this->submission_status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusIcon(): string
    {
        return match($this->submission_status) {
            'draft' => 'heroicon-o-document-text',
            'submitted' => 'heroicon-o-paper-airplane',
            'under_review' => 'heroicon-o-eye',
            'approved' => 'heroicon-o-check-circle',
            'rejected' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle',
        };
    }
}
