/**
 * Script to create a Medusa API key programmatically
 * This will help set up the API key for Laravel sync
 */

const { MedusaApp } = require('@medusajs/framework');

async function createApiKey() {
    try {
        console.log('🔑 Creating Medusa API Key...');
        
        // Initialize Medusa app
        const medusaApp = new MedusaApp({
            projectConfig: {
                databaseUrl: process.env.DATABASE_URL || "postgres://postgres:password@localhost:5432/medusa",
                http: {
                    storeCors: process.env.STORE_CORS || "http://localhost:8000",
                    adminCors: process.env.ADMIN_CORS || "http://localhost:8000",
                    jwtSecret: process.env.JWT_SECRET || "supersecret",
                    cookieSecret: process.env.COOKIE_SECRET || "supersecret",
                }
            }
        });

        // Get the API key service
        const apiKeyService = medusaApp.container.resolve("apiKeyService");
        
        // Create a new API key
        const apiKey = await apiKeyService.create({
            name: "Laravel Sync",
            type: "publishable"
        });

        console.log('✅ API Key created successfully!');
        console.log('🔑 API Key:', apiKey.token);
        console.log('');
        console.log('📝 Add this to your .env file:');
        console.log(`MEDUSA_API_KEY=${apiKey.token}`);
        console.log('');
        console.log('🎉 Your sync will now work perfectly!');
        
    } catch (error) {
        console.error('❌ Error creating API key:', error.message);
        console.log('');
        console.log('📋 Manual setup required:');
        console.log('1. Go to http://localhost:9000/app');
        console.log('2. Login with admin@example.com / password123');
        console.log('3. Go to Settings → API Key Management');
        console.log('4. Create new API key named "Laravel Sync"');
        console.log('5. Copy the key and add to .env file');
    }
}

createApiKey();





