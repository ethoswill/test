# MySQL Connection Troubleshooting

## Error: "Connection refused" (SQLSTATE[HY000] [2002])

This error means MySQL is not running or not accessible.

## Quick Fix

### Step 1: Start MySQL in DBngin

1. **Open DBngin** application
2. **Find MySQL** in the list
3. **Click "Start"** button next to MySQL
4. Wait for the indicator to turn **green** ✅

### Step 2: Verify MySQL is Running

Check if port 3306 is accessible:

```bash
nc -zv 127.0.0.1 3306
```

You should see:
```
Connection to 127.0.0.1 port 3306 [tcp/mysql] succeeded!
```

### Step 3: Create the Database

Once MySQL is running, create the database:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**If `mysql` command is not found:**

1. Find MySQL path in DBngin (usually in `/Applications/DBngin.app/Contents/Resources/`)
2. Use full path: `/Applications/DBngin.app/Contents/Resources/[mysql-version]/mysql/bin/mysql -u root`
3. Or use DBngin's built-in MySQL client

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Switch Back to Database Sessions (Optional)

Once MySQL is working, you can switch back to database sessions:

```bash
# Update .env
sed -i.bak 's/^SESSION_DRIVER=file/SESSION_DRIVER=database/' .env

# Create sessions table
php artisan session:table
php artisan migrate

# Clear cache
php artisan config:clear
```

## Temporary Workaround

If you need to use the app immediately while setting up MySQL, the session driver has been temporarily switched to `file` storage. This allows the app to run without MySQL, but sessions will be stored in files instead of the database.

## Common Issues

### MySQL Won't Start in DBngin

- **Check if another MySQL is running**: Another MySQL instance might be using port 3306
- **Check DBngin logs**: Look for error messages in DBngin
- **Restart DBngin**: Quit and reopen DBngin
- **Check port conflicts**: Another service might be using port 3306

### Wrong Port

If MySQL is running on a different port:
1. Check DBngin settings for the MySQL port
2. Update `.env`:
   ```env
   DB_PORT=3307  # or whatever port DBngin shows
   ```

### Wrong Password

If MySQL requires a password:
1. Check DBngin MySQL settings
2. Update `.env`:
   ```env
   DB_PASSWORD=your_password
   ```

## Verify Setup

Run this command to test the connection:

```bash
php artisan db:show
```

If successful, you'll see database information. If it fails, MySQL is still not accessible.
