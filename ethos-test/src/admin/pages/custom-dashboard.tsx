import { definePageConfig } from "@medusajs/admin-sdk"

const CustomDashboard = () => {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Custom Dashboard</h1>
      <div className="bg-white rounded-lg shadow p-6">
        <h2 className="text-lg font-semibold mb-4">Welcome to Your Custom Dashboard</h2>
        <p className="text-gray-600 mb-4">
          This is a custom page that you can use to display any information or functionality you need.
        </p>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div className="bg-blue-50 p-4 rounded-lg">
            <h3 className="font-semibold text-blue-800">Quick Stats</h3>
            <p className="text-blue-600">View your store statistics</p>
            <button
              onClick={() => window.open('/app/laravel-integration', '_blank')}
              className="mt-2 text-blue-700 hover:text-blue-900 text-sm font-medium"
            >
              Laravel Integration →
            </button>
          </div>
          <div className="bg-green-50 p-4 rounded-lg">
            <h3 className="font-semibold text-green-800">Recent Orders</h3>
            <p className="text-green-600">Check latest customer orders</p>
          </div>
          <div className="bg-purple-50 p-4 rounded-lg">
            <h3 className="font-semibold text-purple-800">Product Management</h3>
            <p className="text-purple-600">Manage your product catalog</p>
            <button
              onClick={() => window.open('/app/embroidery-management', '_blank')}
              className="mt-2 text-purple-700 hover:text-purple-900 text-sm font-medium"
            >
              Embroidery Management →
            </button>
          </div>
          <div className="bg-yellow-50 p-4 rounded-lg">
            <h3 className="font-semibold text-yellow-800">Customer Milestones</h3>
            <p className="text-yellow-600">Track customer achievements</p>
            <button
              onClick={() => window.open('/app/customer-milestones', '_blank')}
              className="mt-2 text-yellow-700 hover:text-yellow-900 text-sm font-medium"
            >
              View Milestones →
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}

export const config = definePageConfig({
  label: "Custom Dashboard",
  icon: "squares-2x2",
})

export default CustomDashboard
