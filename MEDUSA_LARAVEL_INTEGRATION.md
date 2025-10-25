# Medusa Product Data Integration with Laravel

This guide explains how to access and display Medusa product data in your Laravel application, including the custom embroidery widget data.

## 🚀 Quick Start

### 1. Configuration

The integration is configured in `config/medusa.php` and your `.env` file:

```env
MEDUSA_BASE_URL=http://localhost:9000
MEDUSA_API_KEY=your-api-key-here
MEDUSA_TIMEOUT=30
MEDUSA_CACHE_TTL=300
```

### 2. Start Both Servers

```bash
# Terminal 1: Start Medusa
cd /Users/williamhunt/my-app/ethos-test
npm run dev

# Terminal 2: Start Laravel
cd /Users/williamhunt/my-app
php artisan serve
```

### 3. Access the Integration

- **Laravel Product Browser**: http://localhost:8000/medusa/products
- **Medusa Admin**: http://localhost:9000/app
- **API Endpoints**: http://localhost:8000/api/medusa/products

## 📊 Available Data

### Product Data Structure

Each product from Medusa includes:

```php
[
    'id' => 'prod_123',
    'title' => 'Custom T-Shirt',
    'handle' => 'custom-t-shirt',
    'status' => 'published',
    'description' => 'A customizable t-shirt',
    'thumbnail' => 'https://...',
    'variants' => [...],
    'metadata' => [
        'customization_method' => 'Embroidery',
        'embroidery_data' => [
            'file' => 'base64_encoded_file',
            'fileName' => 'design.svg',
            'fileType' => 'image/svg+xml',
            'width' => 5.2,
            'height' => 3.0,
            'embroideryStyle' => 'Flat',
            'threadColor' => 'Red',
            'embroideryType' => 'Flats',
            'notes' => 'Special instructions...'
        ]
    ]
]
```

## 🔧 API Endpoints

### REST API Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/medusa/products` | Get all products |
| GET | `/api/medusa/products/{id}` | Get specific product |
| GET | `/api/medusa/products/laravel/{laravelId}` | Get product by Laravel ID |
| GET | `/api/medusa/products/embroidery` | Get products with embroidery data |
| GET | `/api/medusa/products/{id}/embroidery` | Get embroidery data for product |
| GET | `/api/medusa/products/search` | Search products |
| GET | `/api/medusa/products/stats` | Get product statistics |
| GET | `/api/medusa/products/customization/{method}` | Get products by customization method |
| GET | `/api/medusa/products/embroidery-type/{type}` | Get products by embroidery type |
| POST | `/api/medusa/products/cache/clear` | Clear cache |

### Web Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/medusa/products` | Product listing page |
| GET | `/medusa/products/{id}` | Product details page |
| GET | `/medusa/products/{id}/embroidery` | Embroidery details page |
| GET | `/medusa/products/customization/{method}` | Products by customization method |
| GET | `/medusa/products/embroidery-type/{type}` | Products by embroidery type |
| GET | `/medusa/products/stats` | Product statistics page |

## 💻 Usage Examples

### 1. Using the Service Class

```php
use App\Services\MedusaDataService;

class YourController extends Controller
{
    protected $medusaDataService;

    public function __construct(MedusaDataService $medusaDataService)
    {
        $this->medusaDataService = $medusaDataService;
    }

    public function index()
    {
        // Get all products
        $products = $this->medusaDataService->getAllProducts();
        
        // Get products with embroidery
        $embroideryProducts = $this->medusaDataService->getProductsWithEmbroidery();
        
        // Get specific product
        $product = $this->medusaDataService->getProduct('prod_123');
        
        // Get embroidery data
        $embroideryData = $this->medusaDataService->getEmbroideryData('prod_123');
        
        // Search products
        $searchResults = $this->medusaDataService->searchProducts([
            'title' => 'shirt',
            'status' => 'published',
            'limit' => 10
        ]);
        
        // Get statistics
        $stats = $this->medusaDataService->getProductStats();
    }
}
```

### 2. Using API Endpoints

```javascript
// Fetch all products
fetch('/api/medusa/products')
    .then(response => response.json())
    .then(data => console.log(data));

// Search products
fetch('/api/medusa/products/search?title=shirt&status=published')
    .then(response => response.json())
    .then(data => console.log(data));

// Get embroidery data
fetch('/api/medusa/products/prod_123/embroidery')
    .then(response => response.json())
    .then(data => console.log(data));
```

### 3. Using in Blade Templates

```blade
@foreach($products['products'] as $product)
    <div class="product-card">
        <h3>{{ $product['title'] }}</h3>
        
        @if(isset($product['metadata']['customization_method']))
            <p>Customization: {{ $product['metadata']['customization_method'] }}</p>
        @endif
        
        @if(isset($product['metadata']['embroidery_data']))
            <div class="embroidery-info">
                <h4>Embroidery Details:</h4>
                <p>Style: {{ $product['metadata']['embroidery_data']['embroideryStyle'] }}</p>
                <p>Color: {{ $product['metadata']['embroidery_data']['threadColor'] }}</p>
                <p>Size: {{ $product['metadata']['embroidery_data']['width'] }}" × {{ $product['metadata']['embroidery_data']['height'] }}"</p>
            </div>
        @endif
    </div>
@endforeach
```

## 🎨 Embroidery Data Structure

The embroidery widget stores data in the product's `metadata.embroidery_data` field:

```php
[
    'file' => 'base64_encoded_file_data',        // Base64 encoded file
    'fileName' => 'design.svg',                  // Original filename
    'fileType' => 'image/svg+xml',              // MIME type
    'width' => 5.2,                             // Width in inches
    'height' => 3.0,                            // Height in inches
    'embroideryStyle' => 'Flat',                // Flat, Puff, or Mix
    'threadColor' => 'Red',                     // Thread color
    'embroideryType' => 'Flats',                // Hats or Flats
    'notes' => 'Special instructions...'        // Additional notes
]
```

## 📈 Statistics Available

The integration provides comprehensive product statistics:

```php
[
    'total_products' => 150,
    'published_products' => 120,
    'draft_products' => 30,
    'products_with_embroidery' => 45,
    'products_with_customization' => 80,
    'total_variants' => 300,
    'price_range' => [
        'min' => 999,  // $9.99
        'max' => 4999  // $49.99
    ]
]
```

## 🔄 Caching

- **Cache TTL**: 5 minutes (configurable)
- **Cache Keys**: 
  - `medusa_products_{hash}` - All products
  - `medusa_product_{id}` - Specific product
- **Clear Cache**: Use the API endpoint or service method

## 🛠️ Customization

### Adding New Metadata Fields

1. Update the embroidery widget in Medusa
2. The data will automatically be available in Laravel
3. Access via `$product['metadata']['your_field']`

### Custom API Endpoints

Create new endpoints in `app/Http/Controllers/Api/MedusaProductController.php`:

```php
public function getCustomData($productId)
{
    $product = $this->medusaDataService->getProduct($productId);
    // Your custom logic here
    return response()->json(['data' => $customData]);
}
```

### Custom Views

Create new Blade templates in `resources/views/medusa/` and add corresponding routes.

## 🚨 Troubleshooting

### Common Issues

1. **Connection Refused**: Ensure Medusa is running on port 9000
2. **No Data**: Check if products exist in Medusa admin
3. **Cache Issues**: Clear cache using the API endpoint
4. **API Key**: Update `MEDUSA_API_KEY` in your `.env` file

### Debug Mode

Enable detailed logging by adding to your `.env`:

```env
LOG_LEVEL=debug
```

### Health Check

Test the connection:

```bash
curl http://localhost:8000/api/medusa/products/stats
```

## 📝 Next Steps

1. **Authentication**: Add API authentication for production
2. **Webhooks**: Set up Medusa webhooks for real-time updates
3. **Sync**: Create a sync mechanism for offline data
4. **Analytics**: Add product analytics and reporting
5. **Custom Fields**: Extend the metadata structure for your needs

## 🔗 Related Files

- `app/Services/MedusaDataService.php` - Main service class
- `app/Http/Controllers/Api/MedusaProductController.php` - API controller
- `app/Http/Controllers/MedusaProductController.php` - Web controller
- `config/medusa.php` - Configuration
- `routes/api.php` - API routes
- `routes/web.php` - Web routes
- `resources/views/medusa/` - Blade templates

This integration provides a complete solution for accessing and displaying Medusa product data in your Laravel application, including the custom embroidery widget functionality.






