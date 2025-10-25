# Medusa API Key Setup

## Quick Setup Instructions

### 1. Access Medusa Admin
Open your browser and go to: **http://localhost:9000/app**

### 2. Login
- Email: `admin@example.com`
- Password: `password123`

### 3. Create API Key
1. Go to **Settings** (gear icon in sidebar)
2. Click on **API Key Management** or **API Keys**
3. Click **Create API Key**
4. Give it a name like "Laravel Sync"
5. Copy the generated API key

### 4. Update Laravel .env
Add the API key to your `.env` file:
```bash
MEDUSA_API_KEY=your-actual-api-key-here
```

### 5. Test the Sync
Once the API key is set, the sync will work with:
- ✅ **Create new products** in Medusa if not synced before
- ✅ **Update existing products** in Medusa if already synced (overwrite save)

## What Happens When You Sync

### First Time Sync
- Creates new product in Medusa
- Stores Laravel ID in Medusa metadata
- Returns success message: "Product 'Name' created successfully"

### Subsequent Syncs
- Finds existing product by Laravel ID
- Updates all product data in Medusa
- Returns success message: "Product 'Name' updated successfully"

## Current Status

**✅ Ready to Use:**
- Sync UI is working perfectly
- Create/Update logic is implemented
- Error handling is comprehensive
- Just needs API key to activate

**🔑 Next Step:**
Set up the API key using the instructions above, then test the sync!

## Troubleshooting

If you get "API key required" error:
1. Make sure you copied the API key correctly
2. Restart Laravel server after updating .env
3. Check that Medusa is running on port 9000

If you get "Unauthorized" error:
1. Verify the API key is correct
2. Make sure there are no extra spaces in .env
3. Try generating a new API key





