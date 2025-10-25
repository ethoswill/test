import { defineWidgetConfig } from "@medusajs/admin-sdk"
import { useEffect } from "react"

const CustomNavigationWidget = () => {
  useEffect(() => {
    // Wait for the navigation to load
    const hideNavigationItems = () => {
      // Find the navigation container
      const navContainer = document.querySelector('[data-testid="sidebar"]') || 
                          document.querySelector('nav') ||
                          document.querySelector('[role="navigation"]')
      
      if (!navContainer) return

      // List of items to hide (keep only Products and Stores)
      const itemsToHide = [
        'Search',
        'Orders', 
        'Collections',
        'Categories',
        'Inventory',
        'Customers',
        'Promotions',
        'Price Lists',
        'Settings'
      ]

      // Hide unwanted navigation items
      itemsToHide.forEach(itemName => {
        const items = navContainer.querySelectorAll('a, button, [role="menuitem"]')
        items.forEach(item => {
          const text = item.textContent?.trim()
          if (text && itemsToHide.some(hideItem => text.includes(hideItem))) {
            const listItem = item.closest('li') || item.closest('[role="menuitem"]')
            if (listItem) {
              listItem.style.display = 'none'
            }
          }
        })
      })

      // Also try to hide by data attributes or specific selectors
      const selectorsToHide = [
        '[href*="/orders"]',
        '[href*="/collections"]', 
        '[href*="/categories"]',
        '[href*="/inventory"]',
        '[href*="/customers"]',
        '[href*="/promotions"]',
        '[href*="/price-lists"]',
        '[href*="/settings"]',
        '[data-testid*="search"]',
        '[data-testid*="orders"]',
        '[data-testid*="collections"]',
        '[data-testid*="categories"]',
        '[data-testid*="inventory"]',
        '[data-testid*="customers"]',
        '[data-testid*="promotions"]',
        '[data-testid*="price-lists"]',
        '[data-testid*="settings"]'
      ]

      selectorsToHide.forEach(selector => {
        const elements = navContainer.querySelectorAll(selector)
        elements.forEach(element => {
          const listItem = element.closest('li') || element.closest('[role="menuitem"]')
          if (listItem) {
            listItem.style.display = 'none'
          }
        })
      })
    }

    // Run immediately and also set up a mutation observer
    hideNavigationItems()

    // Set up mutation observer to handle dynamic content
    const observer = new MutationObserver(() => {
      hideNavigationItems()
    })

    // Start observing
    observer.observe(document.body, {
      childList: true,
      subtree: true
    })

    // Cleanup
    return () => {
      observer.disconnect()
    }
  }, [])

  return null // This widget doesn't render anything visible
}

export const config = defineWidgetConfig({
  zone: "sidebar.before",
})

export default CustomNavigationWidget
