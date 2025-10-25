<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-info h1 {
            margin: 0;
            color: #333;
            font-size: 28px;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        .po-info {
            text-align: right;
        }
        .po-info h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .po-info p {
            margin: 5px 0;
            color: #666;
        }
        .client-info {
            margin-bottom: 30px;
        }
        .client-info h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }
        .client-info p {
            margin: 5px 0;
            color: #666;
        }
        .line-items {
            margin-bottom: 30px;
        }
        .line-items h3 {
            margin: 0 0 15px 0;
            color: #333;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            padding: 8px 12px;
            border: none;
        }
        .totals .total-row {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 16px;
        }
        .notes {
            margin-top: 30px;
        }
        .notes h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }
        .notes p {
            margin: 5px 0;
            color: #666;
        }
        .terms {
            margin-top: 20px;
        }
        .terms h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 18px;
        }
        .terms p {
            margin: 5px 0;
            color: #666;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>Ethos Merch</h1>
            <p>123 Business Street</p>
            <p>City, State 12345</p>
            <p>Phone: (555) 123-4567</p>
            <p>Email: orders@ethosmerch.com</p>
        </div>
        <div class="po-info">
            <h2>PURCHASE ORDER</h2>
            <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
            <p><strong>Date:</strong> {{ $purchaseOrder->po_date->format('M d, Y') }}</p>
            @if($purchaseOrder->delivery_date)
                <p><strong>Delivery Date:</strong> {{ $purchaseOrder->delivery_date->format('M d, Y') }}</p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($purchaseOrder->status) }}</p>
        </div>
    </div>

    <div class="client-info">
        <h3>Vendor Information</h3>
        <p><strong>{{ $purchaseOrder->vendor->name }}</strong></p>
        @if($purchaseOrder->vendor->email)
            <p>Email: {{ $purchaseOrder->vendor->email }}</p>
        @endif
        @if($purchaseOrder->vendor->phone)
            <p>Phone: {{ $purchaseOrder->vendor->phone }}</p>
        @endif
        @if($purchaseOrder->vendor->address)
            <p>Address: {{ $purchaseOrder->vendor->address }}</p>
        @endif
    </div>

    @if($purchaseOrder->description)
        <div class="notes">
            <h3>Description</h3>
            <p>{{ $purchaseOrder->description }}</p>
        </div>
    @endif

    <div class="line-items">
        <h3>Items Ordered</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchaseOrder->line_items as $item)
                    <tr>
                        <td>{{ $item['description'] ?? '' }}</td>
                        <td class="text-right">{{ number_format($item['quantity'] ?? 0, 0) }}</td>
                        <td class="text-right">${{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                        <td class="text-right">${{ number_format(($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">${{ number_format($purchaseOrder->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Tax ({{ number_format($purchaseOrder->tax_rate, 1) }}%):</td>
                <td class="text-right">${{ number_format($purchaseOrder->tax_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td class="text-right">${{ number_format($purchaseOrder->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($purchaseOrder->notes)
        <div class="notes">
            <h3>Notes</h3>
            <p>{{ $purchaseOrder->notes }}</p>
        </div>
    @endif

    @if($purchaseOrder->terms_conditions)
        <div class="terms">
            <h3>Terms & Conditions</h3>
            <p>{{ $purchaseOrder->terms_conditions }}</p>
        </div>
    @endif

    @if($purchaseOrder->delivery_address)
        <div class="notes">
            <h3>Delivery Address</h3>
            <p>{{ $purchaseOrder->delivery_address }}</p>
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This purchase order is valid for 30 days from the date of issue.</p>
    </div>
</body>
</html>
