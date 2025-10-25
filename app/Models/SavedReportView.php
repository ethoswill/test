<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedReportView extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'report_type',
        'visible_columns',
        'filters',
        'sort',
        'is_default',
    ];

    protected $casts = [
        'visible_columns' => 'array',
        'filters' => 'array',
        'sort' => 'array',
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getDefaultView($userId, $reportType = 'product_sales')
    {
        return self::where('user_id', $userId)
            ->where('report_type', $reportType)
            ->where('is_default', true)
            ->first();
    }

    public static function getUserViews($userId, $reportType = 'product_sales')
    {
        return self::where('user_id', $userId)
            ->where('report_type', $reportType)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();
    }
}
