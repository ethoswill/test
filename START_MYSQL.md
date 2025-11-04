# 🚨 MySQL Must Be Running

The application **requires MySQL to be running** because:
- User authentication needs the `users` table
- All Filament admin resources need database access
- The app cannot function without database connectivity

## Quick Start MySQL in DBngin

### Step 1: Open DBngin
1. Open the **DBngin** application on your Mac
2. Look for **MySQL** in the list of database servers

### Step 2: Start MySQL
1. Click the **"Start"** button next to MySQL
2. Wait for the status indicator to turn **green** ✅
3. You should see the status change to "Running"

### Step 3: Verify MySQL is Running

Open Terminal and run:
```bash
nc -zv 127.0.0.1 3306
```

You should see:
```
Connection to 127.0.0.1 port 3306 [tcp/mysql] succeeded!
```

### Step 4: Create Database (First Time Only)

If this is your first setup, create the database:

**Option A: Using mysql command (if available in PATH)**
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Option B: Find MySQL in DBngin**
1. In DBngin, click on the MySQL server
2. Look for the "Open MySQL Client" or "Connect" button
3. Or check the MySQL path: `/Applications/DBngin.app/Contents/Resources/[mysql-version]/mysql/bin/mysql`

**Option C: Use DBngin's built-in client**
- DBngin often has a built-in MySQL client you can access from the UI

### Step 5: Run Migrations

Once MySQL is running and the database exists:
```bash
php artisan migrate
```

This will create all the necessary tables including `users`, `files`, etc.

### Step 6: Refresh Your Browser

After MySQL is running and migrations are complete, refresh your browser. The app should work!

## Troubleshooting

### "Connection refused" error persists

1. **Check DBngin status**: Make sure MySQL shows as "Running" (green)
2. **Check port**: Verify MySQL is on port 3306 (default)
3. **Restart DBngin**: Quit and reopen the DBngin app
4. **Check for conflicts**: Another MySQL instance might be using port 3306

### MySQL won't start in DBngin

- Check DBngin's error logs
- Try stopping and starting again
- Restart the DBngin application
- Check system logs: `Console.app` → Search for "mysql" or "DBngin"

### Can't find mysql command

The `mysql` command might not be in your PATH. Find it:

```bash
# Search for mysql binary
find /Applications/DBngin.app -name mysql 2>/dev/null

# Or check common DBngin locations
ls -la /Applications/DBngin.app/Contents/Resources/*/mysql/bin/mysql 2>/dev/null
```

Once found, use the full path:
```bash
/Applications/DBngin.app/Contents/Resources/[version]/mysql/bin/mysql -u root -e "CREATE DATABASE my_app_db;"
```

## Why This Error Happens

The error `SQLSTATE[HY000] [2002] Connection refused` means:
- Laravel is trying to connect to MySQL at `127.0.0.1:3306`
- But MySQL is not running or not accessible
- Authentication middleware tries to load the user from the database
- Without database access, the app cannot authenticate users

**Bottom line**: MySQL must be running for the application to work.

## Quick Test

Once MySQL is running, test the connection:
```bash
php artisan db:show
```

If successful, you'll see database information. Then refresh your browser!
