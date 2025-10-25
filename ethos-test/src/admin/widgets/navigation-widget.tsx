import { defineWidgetConfig } from "@medusajs/admin-sdk"

const NavigationWidget = () => {
  const navigationItems = [
    {
      title: "Custom Dashboard",
      description: "Main dashboard with quick access to all features",
      url: "/app/custom-dashboard",
      icon: "🏠",
      color: "bg-blue-50 text-blue-800"
    },
    {
      title: "Customer Milestones",
      description: "Track customer achievements and rewards",
      url: "/app/customer-milestones",
      icon: "🏆",
      color: "bg-yellow-50 text-yellow-800"
    },
    {
      title: "Laravel Integration",
      description: "Manage sync between Laravel and Medusa",
      url: "/app/laravel-integration",
      icon: "🔗",
      color: "bg-green-50 text-green-800"
    },
    {
      title: "Embroidery Management",
      description: "Track embroidery designs and customization",
      url: "/app/embroidery-management",
      icon: "🎨",
      color: "bg-purple-50 text-purple-800"
    }
  ]

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <h2 className="text-lg font-semibold text-gray-900 mb-4">Custom Pages Navigation</h2>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        {navigationItems.map((item, index) => (
          <button
            key={index}
            onClick={() => window.open(item.url, '_blank')}
            className={`p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow text-left ${item.color}`}
          >
            <div className="flex items-center space-x-3">
              <span className="text-2xl">{item.icon}</span>
              <div>
                <h3 className="font-semibold">{item.title}</h3>
                <p className="text-sm opacity-75">{item.description}</p>
              </div>
            </div>
          </button>
        ))}
      </div>
      
      <div className="mt-4 pt-4 border-t border-gray-200">
        <p className="text-sm text-gray-500 text-center">
          💡 <strong>Tip:</strong> Bookmark these URLs for quick access to your custom pages
        </p>
      </div>
    </div>
  )
}

export const config = defineWidgetConfig({
  zone: "product.details.after",
})

export default NavigationWidget


