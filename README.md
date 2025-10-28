# Product Database - Laravel + Filament Admin Panel

A production-ready Laravel application with Filament admin panel for managing a comprehensive product database. Features strong typing, strict validation, clean architecture, and production-ready defaults.

## Features

### 🚀 Core Features
- **Product Management**: Complete CRUD operations for products
- **Advanced Filtering**: Filter by status, category, stock levels, and more
- **Stock Management**: Track inventory levels with low stock alerts
- **Image Upload**: Multiple image support with image editor
- **Specifications**: Key-value pairs for product specifications
- **Profit Margin Calculation**: Automatic profit margin calculation
- **Featured Products**: Mark products as featured
- **Bulk Operations**: Bulk actions for multiple products

### 🛡️ Security Features
- **Security Headers**: Comprehensive security headers middleware
- **Content Security Policy**: CSP implementation
- **Input Validation**: Strict validation rules for all inputs
- **SQL Injection Protection**: Eloquent ORM with parameterized queries
- **XSS Protection**: Built-in XSS protection
- **CSRF Protection**: Laravel's built-in CSRF protection

### 📊 Admin Panel Features
- **Modern UI**: Clean, responsive Filament admin interface
- **Real-time Updates**: Live form validation and updates
- **Advanced Search**: Search across multiple fields
- **Sorting & Pagination**: Efficient data handling
- **Export Capabilities**: Built-in export functionality
- **Navigation Badges**: Product count and low stock alerts

## Technology Stack

- **Laravel 12**: Latest Laravel framework
- **Filament 3**: Modern admin panel
- **SQLite/MySQL**: Database support
- **PHP 8.3+**: Modern PHP features
- **Livewire**: Reactive components
- **Tailwind CSS**: Utility-first CSS framework

## Installation

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & NPM (for asset compilation)
- SQLite (default) or MySQL/PostgreSQL

### Local Development Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd product-db
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=ProductSeeder
   ```

5. **Create admin user**
   ```bash
   php artisan make:filament-user
   ```

6. **Start development server**
   ```bash
   php artisan serve
   npm run dev
   ```

7. **Access the admin panel**
   - URL: `http://localhost:8000/admin`
   - Login with the admin credentials you created

## Production Deployment

### Environment Configuration

1. **Update `.env` for production**
   ```bash
   cp .env.production .env
   ```

2. **Configure production settings**
   - Update database credentials
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure mail settings
   - Set secure session and cache drivers

3. **Security considerations**
   - Use HTTPS in production
   - Set strong database passwords
   - Configure proper file permissions
   - Enable security headers
   - Use Redis for caching and sessions

### Deployment Steps

1. **Server requirements**
   - PHP 8.3+
   - Web server (Apache/Nginx)
   - Database (MySQL/PostgreSQL)
   - Redis (recommended)

2. **Deployment commands**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan migrate --force
   ```

3. **File permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

## Database Schema

### Products Table
- `id`: Primary key
- `name`: Product name (required, max 255 chars)
- `sku`: Stock Keeping Unit (required, unique, max 100 chars)
- `description`: Product description (nullable)
- `price`: Selling price (required, decimal 10,2)
- `cost`: Cost price (nullable, decimal 10,2)
- `stock_quantity`: Current stock (required, integer, default 0)
- `min_stock_level`: Minimum stock alert level (required, integer, default 0)
- `category`: Product category (nullable, max 100 chars)
- `brand`: Product brand (nullable, max 100 chars)
- `status`: Product status (active/inactive/discontinued)
- `images`: JSON array of image paths
- `specifications`: JSON key-value pairs
- `weight`: Product weight in kg (nullable, decimal 8,2)
- `dimensions`: Product dimensions (nullable, max 50 chars)
- `barcode`: Product barcode (nullable, max 50 chars)
- `is_featured`: Featured product flag (boolean, default false)
- `published_at`: Publication date (nullable, timestamp)
- `created_at`: Creation timestamp
- `updated_at`: Last update timestamp

## API Endpoints

The application provides RESTful API endpoints for product management:

- `GET /api/products` - List all products
- `POST /api/products` - Create new product
- `GET /api/products/{id}` - Get specific product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

## Testing

Run the test suite:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions:
- Create an issue in the repository
- Check the Laravel documentation
- Check the Filament documentation

## Changelog

### Version 1.0.0
- Initial release
- Product management system
- Filament admin panel
- Security middleware
- Sample data seeder
- Production-ready configuration

- Auto Deploy Test
