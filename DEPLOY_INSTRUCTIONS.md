# Deployment Instructions

## To deploy changes to live server (cad.clickoapps.com)

### Option 1: SSH into the server and pull changes

1. SSH into your live server:
   ```bash
   ssh user@cad.clickoapps.com
   # or whatever your SSH connection string is
   ```

2. Navigate to your application directory:
   ```bash
   cd /path/to/your/application
   ```

3. Pull the latest changes:
   ```bash
   git pull product-db main
   # or if using origin:
   git pull origin main
   ```

4. Clear Laravel caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

5. If needed, rebuild assets:
   ```bash
   npm run build
   ```

### Option 2: If using a deployment script

Run your deployment script on the server.

### Option 3: If using CI/CD

The changes should automatically deploy if you have CI/CD set up (GitHub Actions, etc.).

---

**Note:** The changes pushed to GitHub include:
- Fixed file upload handling for color codes
- Made CAD Download URL field optional (nullable)

Make sure to pull these changes on the live server for them to take effect.

