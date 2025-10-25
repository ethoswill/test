<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSalesReport extends Model
{
    protected $fillable = [
        'product_id',
        'product_name',
        'supplier_name',
        'vendor_name',
        'store_name',
        'cost_price',
        'retail_price',
        'profit_margin',
        'profit_percentage',
        'quantity_sold',
        'total_revenue',
        'total_cost',
        'total_profit',
        'report_date',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'quantity_sold' => 'integer',
        'total_revenue' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'report_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Static method to generate sales report data
    public static function generateReport($startDate = null, $endDate = null)
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        // This would typically pull from actual sales data
        // For now, we'll use the products table as our data source
        $products = Product::with(['supplier', 'vendor', 'store'])->get();

        $reports = [];
        foreach ($products as $product) {
            // Mock sales data - in a real app, this would come from actual sales records
            $quantitySold = rand(1, 100);
            
            // Use cost_price if available, otherwise fall back to ethos_cost_price
            $costPrice = $product->cost_price ?? $product->ethos_cost_price ?? rand(10, 50);
            
            // Use retail_price if available, otherwise fall back to customer_dtc_price
            $retailPrice = $product->retail_price ?? $product->customer_dtc_price ?? rand(20, 100);
            
            $profitMargin = $retailPrice - $costPrice;
            $profitPercentage = $costPrice > 0 ? ($profitMargin / $costPrice) * 100 : 0;
            $totalRevenue = $retailPrice * $quantitySold;
            $totalCost = $costPrice * $quantitySold;
            $totalProfit = $totalRevenue - $totalCost;

            $reports[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'supplier_name' => $product->supplier->name ?? 'N/A',
                'vendor_name' => $product->vendor->name ?? 'N/A',
                'store_name' => $product->store->name ?? 'N/A',
                'cost_price' => $costPrice,
                'retail_price' => $retailPrice,
                'profit_margin' => $profitMargin,
                'profit_percentage' => $profitPercentage,
                'quantity_sold' => $quantitySold,
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'report_date' => now(),
            ];
        }

        return $reports;
    }
}
