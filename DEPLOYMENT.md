# 🚀 Deployment Guide

## Railway Deployment (Recommended)

### Step 1: Prepare Your Repository
1. Push your code to GitHub
2. Make sure all files are committed

### Step 2: Deploy to Railway
1. Go to [railway.app](https://railway.app)
2. Sign up with GitHub
3. Click "New Project" → "Deploy from GitHub repo"
4. Select your repository
5. Railway will automatically detect it's a Laravel app

### Step 3: Configure Environment Variables
In Railway dashboard, go to Variables tab and add:

```
APP_NAME=Ethos Admin Panel
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Step 4: Add PostgreSQL Database
1. In Railway dashboard, click "New" → "Database" → "PostgreSQL"
2. Railway will automatically set the DB_* environment variables

### Step 5: Deploy
1. Railway will automatically build and deploy
2. The app will be available at your Railway URL

## Alternative: Heroku Deployment

### Step 1: Install Heroku CLI
```bash
# macOS
brew install heroku/brew/heroku

# Or download from https://devcenter.heroku.com/articles/heroku-cli
```

### Step 2: Login and Create App
```bash
heroku login
heroku create your-app-name
```

### Step 3: Add PostgreSQL
```bash
heroku addons:create heroku-postgresql:mini
```

### Step 4: Set Environment Variables
```bash
heroku config:set APP_NAME="Ethos Admin Panel"
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
```

### Step 5: Deploy
```bash
git push heroku main
heroku run php artisan migrate
heroku run php artisan storage:link
```

## Post-Deployment Steps

1. **Run Migrations**: `php artisan migrate`
2. **Create Storage Link**: `php artisan storage:link`
3. **Clear Caches**: `php artisan optimize`
4. **Set Admin User**: Create your admin account

## Access Your Deployed App

Once deployed, you can access:
- **Main App**: `https://your-app-name.railway.app`
- **Admin Panel**: `https://your-app-name.railway.app/admin`

## Features Available in Production

✅ **File Manager**: Upload and manage images
✅ **Store Management**: Create stores with logo assignment
✅ **Product Management**: Manage products
✅ **Professional UI**: Clean, responsive interface
✅ **Image Previews**: Full image preview functionality
✅ **No Pinwheeling**: Optimized for production performance

## Troubleshooting

### If images don't load:
1. Check storage link: `php artisan storage:link`
2. Verify file permissions
3. Check APP_URL is set correctly

### If database errors:
1. Run migrations: `php artisan migrate`
2. Check database connection
3. Verify environment variables

### If admin panel doesn't work:
1. Create admin user: `php artisan make:filament-user`
2. Check APP_URL setting
3. Clear caches: `php artisan optimize:clear`
