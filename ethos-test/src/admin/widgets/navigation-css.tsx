import { defineWidgetConfig } from "@medusajs/admin-sdk"
import { useEffect } from "react"

const NavigationCSSWidget = () => {
  useEffect(() => {
    // Add custom CSS to hide unwanted navigation items
    const style = document.createElement('style')
    style.textContent = `
      /* Hide unwanted navigation items */
      [data-testid="sidebar"] a[href*="/orders"],
      [data-testid="sidebar"] a[href*="/collections"],
      [data-testid="sidebar"] a[href*="/categories"],
      [data-testid="sidebar"] a[href*="/inventory"],
      [data-testid="sidebar"] a[href*="/customers"],
      [data-testid="sidebar"] a[href*="/promotions"],
      [data-testid="sidebar"] a[href*="/price-lists"],
      [data-testid="sidebar"] a[href*="/settings"],
      [data-testid="sidebar"] button[aria-label*="Search"],
      [data-testid="sidebar"] button[aria-label*="Orders"],
      [data-testid="sidebar"] button[aria-label*="Collections"],
      [data-testid="sidebar"] button[aria-label*="Categories"],
      [data-testid="sidebar"] button[aria-label*="Inventory"],
      [data-testid="sidebar"] button[aria-label*="Customers"],
      [data-testid="sidebar"] button[aria-label*="Promotions"],
      [data-testid="sidebar"] button[aria-label*="Price Lists"],
      [data-testid="sidebar"] button[aria-label*="Settings"],
      /* Hide by text content */
      [data-testid="sidebar"] a:has-text("Orders"),
      [data-testid="sidebar"] a:has-text("Collections"),
      [data-testid="sidebar"] a:has-text("Categories"),
      [data-testid="sidebar"] a:has-text("Inventory"),
      [data-testid="sidebar"] a:has-text("Customers"),
      [data-testid="sidebar"] a:has-text("Promotions"),
      [data-testid="sidebar"] a:has-text("Price Lists"),
      [data-testid="sidebar"] a:has-text("Settings"),
      /* Hide parent list items */
      [data-testid="sidebar"] li:has(a[href*="/orders"]),
      [data-testid="sidebar"] li:has(a[href*="/collections"]),
      [data-testid="sidebar"] li:has(a[href*="/categories"]),
      [data-testid="sidebar"] li:has(a[href*="/inventory"]),
      [data-testid="sidebar"] li:has(a[href*="/customers"]),
      [data-testid="sidebar"] li:has(a[href*="/promotions"]),
      [data-testid="sidebar"] li:has(a[href*="/price-lists"]),
      [data-testid="sidebar"] li:has(a[href*="/settings"]),
      /* Alternative selectors */
      nav a[href*="/orders"],
      nav a[href*="/collections"],
      nav a[href*="/categories"],
      nav a[href*="/inventory"],
      nav a[href*="/customers"],
      nav a[href*="/promotions"],
      nav a[href*="/price-lists"],
      nav a[href*="/settings"],
      nav li:has(a[href*="/orders"]),
      nav li:has(a[href*="/collections"]),
      nav li:has(a[href*="/categories"]),
      nav li:has(a[href*="/inventory"]),
      nav li:has(a[href*="/customers"]),
      nav li:has(a[href*="/promotions"]),
      nav li:has(a[href*="/price-lists"]),
      nav li:has(a[href*="/settings"]) {
        display: none !important;
      }
      
      /* Hide search icon */
      [data-testid="sidebar"] button[aria-label*="Search"],
      [data-testid="sidebar"] svg[data-testid*="search"],
      nav button[aria-label*="Search"],
      nav svg[data-testid*="search"] {
        display: none !important;
      }
    `
    
    document.head.appendChild(style)
    
    // Also use JavaScript to hide items by text content
    const hideByText = () => {
      const navContainer = document.querySelector('[data-testid="sidebar"]') || document.querySelector('nav')
      if (!navContainer) return
      
      const itemsToHide = ['Orders', 'Collections', 'Categories', 'Inventory', 'Customers', 'Promotions', 'Price Lists', 'Settings', 'Search']
      
      itemsToHide.forEach(text => {
        const elements = Array.from(navContainer.querySelectorAll('a, button, span, div'))
        elements.forEach(element => {
          if (element.textContent?.trim() === text) {
            const listItem = element.closest('li') || element.closest('[role="menuitem"]')
            if (listItem) {
              listItem.style.display = 'none'
            }
          }
        })
      })
    }
    
    // Run immediately and set up observer
    hideByText()
    
    const observer = new MutationObserver(hideByText)
    observer.observe(document.body, { childList: true, subtree: true })
    
    return () => {
      observer.disconnect()
      if (style.parentNode) {
        style.parentNode.removeChild(style)
      }
    }
  }, [])

  return null
}

export const config = defineWidgetConfig({
  zone: "sidebar.before",
})

export default NavigationCSSWidget
