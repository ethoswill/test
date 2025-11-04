# PHP Upload Size Fix

## Issue
The file upload is failing because PHP upload limits are too restrictive:
- `upload_max_filesize`: 2M (too small for 7.2MB files)
- `post_max_size`: 8M

## Solution

### For Local Development (php artisan serve)

You need to restart the server with increased PHP limits:

```bash
php -d upload_max_filesize=50M -d post_max_size=52M artisan serve --port=8000
```

Or create a script to start the server with these settings.

### For Production (Apache/Nginx)

1. Create or edit `.user.ini` in the project root (already created)
2. Or edit `php.ini` and set:
   ```
   upload_max_filesize = 50M
   post_max_size = 52M
   ```
3. Restart PHP-FPM or Apache

### Quick Fix

If you're using `php artisan serve`, stop the current server (Ctrl+C) and restart with:

```bash
php -d upload_max_filesize=50M -d post_max_size=52M artisan serve --port=8000
```

Then try uploading the file again.

