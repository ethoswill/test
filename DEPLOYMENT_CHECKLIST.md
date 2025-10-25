# ✅ Deployment Checklist

## Pre-Deployment
- [ ] Code is committed to Git
- [ ] All features tested locally
- [ ] Environment variables documented
- [ ] Database migrations ready

## Railway Deployment (Easiest)
- [ ] Create Railway account at railway.app
- [ ] Connect GitHub repository
- [ ] Add PostgreSQL database
- [ ] Set environment variables
- [ ] Deploy and test

## Environment Variables to Set
```
APP_NAME=Ethos Admin Panel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

## Post-Deployment
- [ ] Run migrations: `php artisan migrate`
- [ ] Create storage link: `php artisan storage:link`
- [ ] Create admin user: `php artisan make:filament-user`
- [ ] Test all features
- [ ] Verify image uploads work
- [ ] Test store creation with logos

## Features to Test
- [ ] Admin login works
- [ ] File Manager uploads images
- [ ] Store creation with logo selection
- [ ] Image previews display correctly
- [ ] No pinwheeling issues
- [ ] All CRUD operations work

## Quick Commands
```bash
# Create admin user
php artisan make:filament-user

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Clear caches
php artisan optimize:clear
```

## Support
If you encounter issues:
1. Check Railway logs
2. Verify environment variables
3. Test database connection
4. Check file permissions
