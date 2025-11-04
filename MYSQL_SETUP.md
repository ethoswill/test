# MySQL Setup with DBngin

This project uses **MySQL** with **DBngin** for local database management.

## Quick Setup

### 1. Start MySQL in DBngin

1. Open **DBngin** application
2. Click **Start** next to MySQL
3. Ensure MySQL is running (green indicator)

### 2. Run Setup Script

```bash
./setup-mysql.sh
```

This script will:
- Configure `.env` to use MySQL
- Set database connection settings
- Clear configuration cache

### 3. Create Database

If the database doesn't exist, create it:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Or** use DBngin's built-in MySQL client:
- Open DBngin
- Click on MySQL
- Use the MySQL client to run:
  ```sql
  CREATE DATABASE IF NOT EXISTS my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

### 4. Run Migrations

```bash
php artisan migrate
```

## Database Configuration

Default settings (configured in `.env`):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_app_db
DB_USERNAME=root
DB_PASSWORD=
```

## Troubleshooting

### Connection Refused

If you see "Connection refused" error:

1. **Check DBngin**: Ensure MySQL is started (green indicator)
2. **Check Port**: Default MySQL port is 3306. Verify in DBngin settings
3. **Check Database**: Ensure the database exists:
   ```bash
   mysql -u root -e "SHOW DATABASES;"
   ```

### Access MySQL Command Line

If `mysql` command is not in your PATH:

1. **Find MySQL in DBngin**:
   - DBngin typically installs MySQL at: `/Applications/DBngin.app/Contents/Resources/[version]/mysql/bin/mysql`
   
2. **Add to PATH** (optional):
   ```bash
   export PATH="/Applications/DBngin.app/Contents/Resources/[mysql-version]/mysql/bin:$PATH"
   ```

3. **Or use full path**:
   ```bash
   /Applications/DBngin.app/Contents/Resources/[mysql-version]/mysql/bin/mysql -u root
   ```

### Reset Database

To start fresh:

```bash
php artisan migrate:fresh
```

Or drop and recreate:

```bash
mysql -u root -e "DROP DATABASE IF EXISTS my_app_db;"
mysql -u root -e "CREATE DATABASE my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate
```

## Manual Setup (Without Script)

If you prefer to set up manually:

1. **Update `.env`**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=my_app_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

3. **Create database and run migrations**:
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS my_app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   php artisan migrate
   ```

## Notes

- **Never use SQLite** - This project is configured for MySQL only
- Database credentials are stored in `.env` (never commit this file)
- `.env.example` contains the MySQL template for new setups
