<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseOrderController
{
    public function generatePDF(PurchaseOrder $purchaseOrder)
    {
        $pdf = Pdf::loadView('purchase-orders.pdf', compact('purchaseOrder'));
        
        return $pdf->download('purchase-order-' . $purchaseOrder->po_number . '.pdf');
    }

    public function viewPDF(PurchaseOrder $purchaseOrder)
    {
        $pdf = Pdf::loadView('purchase-orders.pdf', compact('purchaseOrder'));
        
        return $pdf->stream('purchase-order-' . $purchaseOrder->po_number . '.pdf');
    }
}
