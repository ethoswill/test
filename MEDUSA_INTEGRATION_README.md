# Laravel ↔ Medusa Integration

This integration allows you to sync data from your Laravel/Filament application to your Medusa store automatically.

## 🚀 Features

- **Automatic Sync**: Products are automatically synced when created, updated, or deleted
- **Manual Sync**: Sync individual products or all products via Artisan commands
- **Filament Integration**: Sync products directly from the Filament admin interface
- **Embroidery Data**: Full support for embroidery customization data
- **Error Handling**: Comprehensive logging and error handling
- **Flexible Configuration**: Easy to configure and customize

## 📋 Prerequisites

1. **Laravel Application** with Filament
2. **Medusa Store** running on `http://localhost:9000`
3. **PostgreSQL Database** (shared or separate)

## 🔧 Installation

### 1. Add Environment Variables

Add these to your Laravel `.env` file:

```env
# Medusa Store Configuration
MEDUSA_BASE_URL=http://localhost:9000
MEDUSA_API_KEY=your-api-key-here

# Sync Configuration
MEDUSA_SYNC_ENABLED=true
MEDUSA_AUTO_SYNC=false
MEDUSA_SYNC_ON_CREATE=true
MEDUSA_SYNC_ON_UPDATE=true
MEDUSA_SYNC_ON_DELETE=true

# Performance Settings
MEDUSA_TIMEOUT=30
MEDUSA_RETRY_ATTEMPTS=3
MEDUSA_RETRY_DELAY=1000
MEDUSA_BATCH_SIZE=10
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=config
```

### 3. Register Commands

The Artisan commands are automatically registered.

## 🎯 Usage

### Automatic Sync

Products are automatically synced when:
- A new product is created
- An existing product is updated
- A product is deleted

### Manual Sync via Artisan Commands

```bash
# Sync all products
php artisan medusa:sync --all

# Sync specific product
php artisan medusa:sync --product=1

# Update all products
php artisan medusa:sync --update

# Delete product from Medusa
php artisan medusa:sync --delete=1
```

### Manual Sync via Filament

1. Go to your Filament admin panel
2. Navigate to Purchase Orders
3. Click the "Sync to Medusa" button on any product
4. Confirm the sync operation

## 📊 Data Mapping

### Laravel → Medusa Field Mapping

| Laravel Field | Medusa Field | Notes |
|---------------|--------------|-------|
| `id` | `metadata.laravel_id` | Laravel product ID |
| `title` | `title` | Product title |
| `description` | `description` | Product description |
| `price` | `variants[0].prices[0].amount` | Product price |
| `weight` | `weight` | Product weight |
| `length` | `length` | Product length |
| `width` | `width` | Product width |
| `height` | `height` | Product height |
| `hs_code` | `hs_code` | HS code |
| `mid_code` | `mid_code` | MID code |
| `origin_country` | `origin_country` | Country of origin |
| `material` | `material` | Product material |
| `images` | `images` | Product images |
| `embroidery_data` | `metadata.embroidery_data` | Embroidery customization |

### Embroidery Data Structure

```json
{
  "embroidery_data": {
    "width": 2.5,
    "height": 1.0,
    "style": "flat",
    "threadColor": "Navy Blue",
    "type": "flats",
    "fileName": "design.svg",
    "file": "base64data...",
    "notes": "Special instructions"
  }
}
```

## 🔌 API Endpoints

### Medusa Internal API

- `POST /internal/sync-product` - Create/update product
- `PUT /internal/sync-product` - Update existing product
- `DELETE /internal/sync-product` - Delete product

### Request Format

```json
{
  "id": 1,
  "title": "Custom T-Shirt",
  "description": "A customizable t-shirt",
  "handle": "custom-tshirt",
  "status": "published",
  "price": 25.99,
  "currency": "USD",
  "weight": 0.5,
  "images": [
    {
      "url": "https://example.com/image.jpg",
      "alt": "T-Shirt Front"
    }
  ],
  "variants": [
    {
      "title": "Small / Red",
      "sku": "TSHIRT-S-RED",
      "price": 25.99,
      "currency": "USD",
      "options": {
        "Size": "S",
        "Color": "Red"
      }
    }
  ],
  "metadata": {
    "customization_method": "Screen Printing",
    "color": "Red",
    "size": "Small"
  },
  "embroidery_data": {
    "width": 2.5,
    "height": 1.0,
    "style": "flat",
    "threadColor": "Navy Blue",
    "type": "flats"
  }
}
```

## 🛠️ Configuration

### Medusa Configuration

The integration uses Medusa's internal API endpoints. Make sure your Medusa store is configured to accept requests from your Laravel application.

### Laravel Configuration

All configuration is in `config/medusa.php`:

```php
return [
    'base_url' => env('MEDUSA_BASE_URL', 'http://localhost:9000'),
    'api_key' => env('MEDUSA_API_KEY', 'your-api-key-here'),
    'sync' => [
        'enabled' => env('MEDUSA_SYNC_ENABLED', true),
        'auto_sync' => env('MEDUSA_AUTO_SYNC', false),
        'sync_on_create' => env('MEDUSA_SYNC_ON_CREATE', true),
        'sync_on_update' => env('MEDUSA_SYNC_ON_UPDATE', true),
        'sync_on_delete' => env('MEDUSA_SYNC_ON_DELETE', true),
    ],
];
```

## 📝 Logging

All sync operations are logged to Laravel's log files:

- **Success**: Product synced successfully
- **Warning**: Product operation completed but sync failed
- **Error**: Exception during sync operation

Check `storage/logs/laravel.log` for detailed information.

## 🔍 Troubleshooting

### Common Issues

1. **Connection Refused**
   - Ensure Medusa is running on the correct port
   - Check `MEDUSA_BASE_URL` in your `.env`

2. **Authentication Failed**
   - Verify `MEDUSA_API_KEY` is correct
   - Check if Medusa requires authentication

3. **Sync Failures**
   - Check Laravel logs for detailed error messages
   - Verify product data format
   - Ensure required fields are present

4. **Embroidery Data Not Syncing**
   - Verify `embroidery_data` field exists in your model
   - Check JSON format is valid
   - Ensure all required fields are present

### Debug Mode

Enable debug logging by setting `LOG_LEVEL=debug` in your `.env` file.

## 🚀 Advanced Usage

### Custom Field Mapping

Modify `config/medusa.php` to customize field mapping:

```php
'field_mapping' => [
    'title' => 'title',
    'description' => 'description',
    'custom_field' => 'medusa_field',
],
```

### Custom Sync Logic

Override the `MedusaSyncService` methods to implement custom sync logic:

```php
class CustomMedusaSyncService extends MedusaSyncService
{
    protected function formatProductForMedusa($product)
    {
        // Custom formatting logic
    }
}
```

### Batch Operations

For large datasets, use batch operations:

```php
$products = PurchaseOrder::chunk(100, function ($chunk) {
    foreach ($chunk as $product) {
        $this->medusaSyncService->syncProduct($product);
    }
});
```

## 📈 Performance

- **Batch Size**: Configure `MEDUSA_BATCH_SIZE` for optimal performance
- **Timeout**: Adjust `MEDUSA_TIMEOUT` for slow connections
- **Retry Logic**: Built-in retry mechanism with configurable attempts
- **Async Processing**: Consider using queues for large sync operations

## 🔒 Security

- **API Keys**: Store API keys securely in environment variables
- **HTTPS**: Use HTTPS in production
- **Rate Limiting**: Implement rate limiting if needed
- **Validation**: All data is validated before sync

## 📚 Examples

### Sync Single Product

```php
use App\Services\MedusaSyncService;

$medusaSync = app(MedusaSyncService::class);
$product = PurchaseOrder::find(1);
$formattedProduct = $medusaSync->formatProductForMedusa($product);
$result = $medusaSync->syncProduct($formattedProduct);
```

### Sync with Embroidery Data

```php
$product = PurchaseOrder::create([
    'title' => 'Custom Embroidered Hat',
    'price' => 29.99,
    'embroidery_data' => json_encode([
        'width' => 3.0,
        'height' => 1.5,
        'style' => 'puff',
        'threadColor' => 'Navy Blue',
        'type' => 'hats',
        'notes' => 'High quality embroidery'
    ])
]);
// Product will be automatically synced to Medusa
```

This integration provides a seamless way to keep your Laravel and Medusa stores in sync, with full support for embroidery customization data and flexible configuration options.






