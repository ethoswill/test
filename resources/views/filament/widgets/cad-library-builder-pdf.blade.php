<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CAD Library Builder - Combined PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .product-page {
            page-break-after: always;
            margin-bottom: 40px;
        }
        .product-page:last-child {
            page-break-after: avoid;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .product-header {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .product-name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 5px;
        }
        .product-details {
            font-size: 14px;
            color: #6b7280;
        }
        .cad-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .page-number {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    @foreach($products as $index => $product)
    <div class="product-page">
        <div class="product-header">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-details">
                @if($product->product_type)
                    Type: {{ $product->product_type }}
                @endif
                @if($product->supplier)
                    &nbsp;&nbsp;|&nbsp;&nbsp;Supplier: {{ $product->supplier }}
                @endif
                @if($product->base_color)
                    &nbsp;&nbsp;|&nbsp;&nbsp;Base Color: {{ $product->base_color }}
                @endif
            </div>
        </div>
        
        @if($product->cad_download)
            <div style="padding: 20px; border: 2px solid #3b82f6; border-radius: 8px; text-align: center; background-color: #eff6ff;">
                <p style="color: #1e40af; margin: 0; font-weight: bold;">CAD Reference Included</p>
                <p style="color: #6b7280; margin: 5px 0 0 0; font-size: 12px;">The CAD file will be included in this PDF.</p>
            </div>
        @else
            <p style="color: #6b7280; font-style: italic;">No CAD file available for this product.</p>
        @endif
        
        @if($index > 0)
            <div class="page-number">{{ $index + 1 }}</div>
        @endif
    </div>
    @endforeach
</body>
</html>

