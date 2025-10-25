import { loadEnv, defineConfig } from '@medusajs/framework/utils'

loadEnv(process.env.NODE_ENV || 'development', process.cwd())

module.exports = defineConfig({
  projectConfig: {
    databaseUrl: process.env.DATABASE_URL,
    http: {
      storeCors: process.env.STORE_CORS!,
      adminCors: process.env.ADMIN_CORS!,
      authCors: process.env.AUTH_CORS!,
      jwtSecret: process.env.JWT_SECRET || "supersecret",
      cookieSecret: process.env.COOKIE_SECRET || "supersecret",
    }
  },
  admin: {
    path: "/app",
    backendUrl: process.env.MEDUSA_BACKEND_URL || "http://localhost:8000",
    // Custom admin configuration
    customize: {
      // Hide unwanted navigation items
      hideNavigationItems: [
        "search",
        "orders", 
        "collections",
        "categories",
        "inventory",
        "customers",
        "promotions",
        "price-lists",
        "settings"
      ]
    }
  }
})
