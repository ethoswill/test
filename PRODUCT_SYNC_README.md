# Product Sync Between Laravel and Medusa

This integration allows you to sync product data between your Laravel/Filament application and your Medusa store in real-time.

## 🚀 Features

- **Bidirectional Sync**: Sync products from Laravel to Medusa
- **Manual Sync**: Sync individual products or all products via UI buttons
- **Bulk Operations**: Sync selected products or all products at once
- **Connection Testing**: Automatic connection testing before sync operations
- **Error Handling**: Comprehensive error handling and user feedback
- **Flexible Configuration**: Easy to configure and customize

## 📋 Prerequisites

1. **Laravel Application** with Filament running on `http://localhost:8000`
2. **Medusa Store** running on `http://localhost:9000`
3. **API Key** configured for Medusa authentication

## 🔧 Configuration

### 1. Environment Variables

Add these to your Laravel `.env` file:

```env
# Medusa Store Configuration
MEDUSA_BASE_URL=http://localhost:9000
MEDUSA_API_KEY=your-medusa-api-key-here

# Sync Configuration
MEDUSA_SYNC_ENABLED=true
MEDUSA_TIMEOUT=30
MEDUSA_CACHE_TTL=300
```

### 2. Configuration File

The sync service uses `config/medusa.php` for configuration:

```php
return [
    'base_url' => env('MEDUSA_BASE_URL', 'http://localhost:9000'),
    'api_key' => env('MEDUSA_API_KEY', ''),
    'timeout' => env('MEDUSA_TIMEOUT', 30),
    
    'sync' => [
        'enabled' => env('MEDUSA_SYNC_ENABLED', true),
    ],
    
    'mapping' => [
        'status' => [
            'Active' => 'published',
            'Draft' => 'draft',
            'Supplier Product' => 'draft',
        ],
    ]
];
```

## 🎯 Usage

### 1. Sync All Products

**From Laravel Products Page:**
1. Go to `http://localhost:8000/admin/products`
2. Click the "Sync Products" button in the header
3. The system will test the connection to Medusa first
4. If successful, all products will be synced
5. You'll see a notification with the results

### 2. Sync Selected Products

**From Laravel Products Page:**
1. Go to `http://localhost:8000/admin/products`
2. Select one or more products using the checkboxes
3. Click the "Sync Selected" button in the bulk actions dropdown
4. The system will sync only the selected products
5. You'll see a notification with the results

### 3. View Synced Products

**In Medusa Admin:**
1. Go to `http://localhost:9000/app/products`
2. You'll see all synced products from Laravel
3. Products will have the same data as in Laravel

## 📊 Data Mapping

### Laravel → Medusa Field Mapping

| Laravel Field | Medusa Field | Notes |
|---------------|--------------|-------|
| `id` | `metadata.laravel_id` | Laravel product ID |
| `name` | `title` | Product title |
| `supplier_code` | `variants[0].sku` | Product SKU |
| `customer_dtc_price` | `variants[0].price` | Product price |
| `fabric` | `material` | Product material |
| `hs_code` | `hs_code` | HS code |
| `status` | `status` | Mapped: Active→published, Draft→draft |
| `customization_methods` | `metadata.customization_methods` | Customization options |
| All other fields | `metadata.*` | Stored in metadata |

### Product Variants

Each Laravel product creates a default variant in Medusa with:
- **Title**: "Default"
- **SKU**: Supplier code or generated SKU
- **Price**: Customer DTC price or B2B price
- **Options**: Size and Color from available data

## 🔌 API Integration

### Medusa Internal API

The sync uses Medusa's internal API endpoint:
- `POST /internal/sync-product` - Create/update product

### Request Format

```json
{
  "id": 1,
  "title": "Product Name",
  "description": "Generated description",
  "handle": "product-name",
  "status": "published",
  "price": 25.99,
  "currency": "USD",
  "variants": [...],
  "metadata": {
    "laravel_id": 1,
    "supplier_id": 1,
    "customization_methods": "IHP, EMB, PATCH",
    // ... all other Laravel fields
  }
}
```

## 🛠️ Troubleshooting

### Common Issues

1. **Connection Failed**
   - Ensure Medusa is running on `http://localhost:9000`
   - Check `MEDUSA_BASE_URL` in your `.env`
   - Verify `MEDUSA_API_KEY` is correct

2. **Sync Failed**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Verify Medusa is accepting requests
   - Check API key permissions

3. **Products Not Appearing**
   - Check Medusa admin at `http://localhost:9000/app/products`
   - Verify the sync was successful (check notifications)
   - Check Medusa logs for errors

### Testing Connection

The sync service automatically tests the connection before syncing. If the connection fails, you'll see an error notification.

## 📝 Logging

All sync operations are logged to Laravel's log files:
- **Success**: Product synced successfully
- **Error**: Exception during sync operation

Check `storage/logs/laravel.log` for detailed information.

## 🎯 Next Steps

1. **Configure API Key**: Set up your Medusa API key in the `.env` file
2. **Test Connection**: Use the sync buttons to test the connection
3. **Sync Products**: Start syncing your products between systems
4. **Monitor Results**: Check notifications and logs for sync results

## 🔄 Workflow

1. **Create/Update Product** in Laravel (`http://localhost:8000/admin/products`)
2. **Click Sync** button to sync to Medusa
3. **View Product** in Medusa (`http://localhost:9000/app/products`)
4. **Verify Data** matches between both systems

The sync is now fully functional and ready to use! 🎉





