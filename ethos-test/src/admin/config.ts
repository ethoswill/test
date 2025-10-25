import { defineConfig } from "@medusajs/admin-sdk"

export const config = defineConfig({
  // Custom admin configuration
  navigation: {
    // Only show Products and Stores in the sidebar
    items: [
      {
        label: "Products",
        href: "/products",
        icon: "CubeIcon"
      },
      {
        label: "Stores", 
        href: "/stores",
        icon: "BuildingStorefrontIcon"
      }
    ]
  }
})

export default config
