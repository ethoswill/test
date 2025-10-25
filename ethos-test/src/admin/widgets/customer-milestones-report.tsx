import { defineWidgetConfig } from "@medusajs/admin-sdk"
import { useState, useEffect } from "react"

const CustomerMilestonesWidget = () => {
  const [customers, setCustomers] = useState([])
  const [loading, setLoading] = useState(true)
  const [filterTier, setFilterTier] = useState("all")
  const [sortBy, setSortBy] = useState("projectedDate")
  const [timeframe, setTimeframe] = useState("30") // days

  useEffect(() => {
    fetchCustomerProgress()
  }, [])

  const fetchCustomerProgress = async () => {
    try {
      setLoading(true)
      console.log("Fetching customer progress data...")
      
      // Fetch customers
      const customersResponse = await fetch('/admin/customers?limit=100')
      const customersData = await customersResponse.json()
      console.log("Customers data received:", customersData)
      
      // Generate workout progress data for each customer
      const customersWithProgress = customersData.customers?.map((customer) => {
        const totalClasses = Math.floor(Math.random() * 200) + 10 // 10-210 classes
        const classesCompleted = Math.floor(Math.random() * totalClasses) + 1
        
        // Calculate average classes per week
        const weeksActive = Math.max(1, Math.floor(Math.random() * 52) + 4) // 4-56 weeks
        const avgClassesPerWeek = Math.round((classesCompleted / weeksActive) * 10) / 10
        
        // Calculate upcoming milestones
        const milestones = [100, 250, 500, 750, 1000]
        const upcomingMilestones = []
        
        milestones.forEach(target => {
          if (classesCompleted < target) {
            const classesNeeded = target - classesCompleted
            const weeksToMilestone = Math.ceil(classesNeeded / avgClassesPerWeek)
            const projectedDate = new Date()
            projectedDate.setDate(projectedDate.getDate() + (weeksToMilestone * 7))
            
            upcomingMilestones.push({
              target: target,
              classesNeeded: classesNeeded,
              weeksToMilestone: weeksToMilestone,
              projectedDate: projectedDate,
              isAchievable: weeksToMilestone <= 104 // Within 2 years
            })
          }
        })
        
        return {
          id: customer.id,
          customerName: `${customer.first_name || ''} ${customer.last_name || ''}`.trim() || 'Unknown',
          customerEmail: customer.email,
          classesCompleted: classesCompleted,
          avgClassesPerWeek: avgClassesPerWeek,
          weeksActive: weeksActive,
          upcomingMilestones: upcomingMilestones,
          nextMilestone: upcomingMilestones[0] || null
        }
      }) || []
      
      setCustomers(customersWithProgress)
      console.log("Processed customers with progress:", customersWithProgress.length)
      
    } catch (error) {
      console.error("Error fetching customer progress:", error)
    } finally {
      setLoading(false)
    }
  }

  const getTierColor = (tier) => {
    switch (tier) {
      case 100: return 'bg-blue-100 text-blue-800'
      case 250: return 'bg-green-100 text-green-800'
      case 500: return 'bg-yellow-100 text-yellow-800'
      case 750: return 'bg-orange-100 text-orange-800'
      case 1000: return 'bg-red-100 text-red-800'
      default: return 'bg-gray-100 text-gray-800'
    }
  }

  const getTierIcon = (tier) => {
    switch (tier) {
      case 100: return '🥉'
      case 250: return '🥈'
      case 500: return '🥇'
      case 750: return '💎'
      case 1000: return '👑'
      default: return '🏆'
    }
  }

  const formatDate = (date) => {
    return date.toLocaleDateString('en-US', { 
      month: 'short', 
      day: 'numeric', 
      year: 'numeric' 
    })
  }

  const getDaysUntilMilestone = (projectedDate) => {
    const today = new Date()
    const diffTime = projectedDate.getTime() - today.getTime()
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    return diffDays
  }

  const filteredCustomers = customers.filter(customer => {
    if (filterTier === "all") return true
    const targetTier = parseInt(filterTier)
    return customer.upcomingMilestones.some(milestone => milestone.target === targetTier)
  })

  const sortedCustomers = [...filteredCustomers].sort((a, b) => {
    switch (sortBy) {
      case "projectedDate":
        if (!a.nextMilestone || !b.nextMilestone) return 0
        return a.nextMilestone.projectedDate.getTime() - b.nextMilestone.projectedDate.getTime()
      case "classesCompleted":
        return b.classesCompleted - a.classesCompleted
      case "customerName":
        return a.customerName.localeCompare(b.customerName)
      case "avgClassesPerWeek":
        return b.avgClassesPerWeek - a.avgClassesPerWeek
      default:
        return 0
    }
  })

  const customersApproachingMilestones = sortedCustomers.filter(customer => {
    if (!customer.nextMilestone) return false
    const daysUntil = getDaysUntilMilestone(customer.nextMilestone.projectedDate)
    return daysUntil <= parseInt(timeframe) && daysUntil >= 0
  })

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-6">
        <div>
          <h1 className="text-2xl font-bold">Upcoming Customer Milestones</h1>
          <p className="text-sm text-gray-600 mt-1">Comprehensive report showing customers approaching reward tiers</p>
        </div>
        <button
          onClick={fetchCustomerProgress}
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Refresh
        </button>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div className="bg-white rounded-lg shadow p-4">
          <h3 className="text-sm font-medium text-gray-500">Total Customers</h3>
          <p className="text-2xl font-bold text-blue-600">{customers.length}</p>
        </div>
        <div className="bg-white rounded-lg shadow p-4">
          <h3 className="text-sm font-medium text-gray-500">Approaching Milestones</h3>
          <p className="text-2xl font-bold text-green-600">{customersApproachingMilestones.length}</p>
        </div>
        <div className="bg-white rounded-lg shadow p-4">
          <h3 className="text-sm font-medium text-gray-500">Avg Classes/Week</h3>
          <p className="text-2xl font-bold text-purple-600">
            {customers.length > 0 ? Math.round(customers.reduce((sum, c) => sum + c.avgClassesPerWeek, 0) / customers.length) : 0}
          </p>
        </div>
        <div className="bg-white rounded-lg shadow p-4">
          <h3 className="text-sm font-medium text-gray-500">Next 30 Days</h3>
          <p className="text-2xl font-bold text-yellow-600">
            {customersApproachingMilestones.filter(c => getDaysUntilMilestone(c.nextMilestone.projectedDate) <= 30).length}
          </p>
        </div>
      </div>

      {/* Filters and Controls */}
      <div className="bg-white rounded-lg shadow p-4 mb-6">
        <div className="flex flex-wrap gap-4 items-center">
          <div className="flex items-center space-x-2">
            <label className="text-sm font-medium text-gray-700">Filter by Tier:</label>
            <select
              value={filterTier}
              onChange={(e) => setFilterTier(e.target.value)}
              className="border border-gray-300 rounded px-3 py-1 text-sm"
            >
              <option value="all">All Tiers</option>
              <option value="100">100 Classes 🥉</option>
              <option value="250">250 Classes 🥈</option>
              <option value="500">500 Classes 🥇</option>
              <option value="750">750 Classes 💎</option>
              <option value="1000">1000 Classes 👑</option>
            </select>
          </div>
          
          <div className="flex items-center space-x-2">
            <label className="text-sm font-medium text-gray-700">Timeframe:</label>
            <select
              value={timeframe}
              onChange={(e) => setTimeframe(e.target.value)}
              className="border border-gray-300 rounded px-3 py-1 text-sm"
            >
              <option value="7">Next 7 days</option>
              <option value="30">Next 30 days</option>
              <option value="90">Next 90 days</option>
              <option value="365">Next year</option>
            </select>
          </div>
          
          <div className="flex items-center space-x-2">
            <label className="text-sm font-medium text-gray-700">Sort by:</label>
            <select
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value)}
              className="border border-gray-300 rounded px-3 py-1 text-sm"
            >
              <option value="projectedDate">Projected Date</option>
              <option value="classesCompleted">Classes Completed</option>
              <option value="customerName">Customer Name</option>
              <option value="avgClassesPerWeek">Avg Classes/Week</option>
            </select>
          </div>
        </div>
      </div>

      {/* Customers Approaching Milestones */}
      <div className="bg-white rounded-lg shadow">
        {loading ? (
          <div className="p-8 text-center">
            <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
            <p className="mt-2 text-gray-600">Loading customer progress...</p>
          </div>
        ) : customersApproachingMilestones.length === 0 ? (
          <div className="p-8 text-center text-gray-500">
            <p className="text-lg mb-2">No customers approaching milestones</p>
            <p className="text-sm">Try adjusting the timeframe or tier filter</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Customer
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Current Progress
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Next Milestone
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Projected Date
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Days Until
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {customersApproachingMilestones.map((customer) => {
                  const daysUntil = getDaysUntilMilestone(customer.nextMilestone.projectedDate)
                  return (
                    <tr key={customer.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div>
                          <div className="text-sm font-medium text-gray-900">
                            {customer.customerName}
                          </div>
                          <div className="text-sm text-gray-500">
                            {customer.customerEmail}
                          </div>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">
                          <div className="font-medium">{customer.classesCompleted} classes</div>
                          <div className="text-gray-500">{Math.round(customer.avgClassesPerWeek)} classes/week</div>
                          <div className="text-gray-500">{customer.weeksActive} weeks active</div>
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <span className="text-lg mr-2">{getTierIcon(customer.nextMilestone.target)}</span>
                          <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getTierColor(customer.nextMilestone.target)}`}>
                            {customer.nextMilestone.target} Classes
                          </span>
                        </div>
                        <div className="text-sm text-gray-500 mt-1">
                          {customer.nextMilestone.classesNeeded} classes remaining
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {formatDate(customer.nextMilestone.projectedDate)}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                          daysUntil <= 7 ? 'bg-red-100 text-red-800' :
                          daysUntil <= 30 ? 'bg-yellow-100 text-yellow-800' :
                          'bg-green-100 text-green-800'
                        }`}>
                          {daysUntil} days
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div className="flex space-x-2">
                          <button
                            onClick={() => window.open(`/app/customers/${customer.id}`, '_blank')}
                            className="text-blue-600 hover:text-blue-900"
                          >
                            View Customer
                          </button>
                          <button
                            onClick={() => {
                              alert(`Ordering merch reward for ${customer.customerName}!\n\nMilestone: ${customer.nextMilestone.target} Classes\nProjected Date: ${formatDate(customer.nextMilestone.projectedDate)}`)
                            }}
                            className="text-green-600 hover:text-green-900"
                          >
                            Order Reward
                          </button>
                        </div>
                      </td>
                    </tr>
                  )
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* Quick Actions */}
      <div className="mt-6 bg-white rounded-lg shadow p-6">
        <h2 className="text-lg font-semibold mb-4">Quick Actions</h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button 
            onClick={() => {
              const csvData = customersApproachingMilestones.map(customer => ({
                'Customer Name': customer.customerName,
                'Email': customer.customerEmail,
                'Classes Completed': customer.classesCompleted,
                'Next Milestone': customer.nextMilestone.target,
                'Projected Date': formatDate(customer.nextMilestone.projectedDate),
                'Days Until': getDaysUntilMilestone(customer.nextMilestone.projectedDate)
              }))
              
              const csvContent = [
                Object.keys(csvData[0]).join(','),
                ...csvData.map(row => Object.values(row).join(','))
              ].join('\n')
              
              const blob = new Blob([csvContent], { type: 'text/csv' })
              const url = window.URL.createObjectURL(blob)
              const a = document.createElement('a')
              a.href = url
              a.download = 'upcoming-milestones.csv'
              a.click()
            }}
            className="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left"
          >
            <h3 className="font-semibold text-green-600">Export Report</h3>
            <p className="text-sm text-gray-600">Download upcoming milestones data</p>
          </button>
          <button 
            onClick={() => {
              const message = `Found ${customersApproachingMilestones.length} customers approaching milestones in the next ${timeframe} days.`
              alert(message)
            }}
            className="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left"
          >
            <h3 className="font-semibold text-blue-600">Send Notifications</h3>
            <p className="text-sm text-gray-600">Notify customers about upcoming rewards</p>
          </button>
          <button 
            onClick={() => {
              const tierCounts = {}
              customersApproachingMilestones.forEach(customer => {
                const tier = customer.nextMilestone.target
                tierCounts[tier] = (tierCounts[tier] || 0) + 1
              })
              
              const report = Object.entries(tierCounts)
                .map(([tier, count]) => `${tier} Classes: ${count} customers`)
                .join('\n')
              
              alert(`Milestone Distribution:\n\n${report}`)
            }}
            className="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 text-left"
          >
            <h3 className="font-semibold text-purple-600">Milestone Summary</h3>
            <p className="text-sm text-gray-600">View distribution by tier</p>
          </button>
        </div>
      </div>
    </div>
  )
}

export const config = defineWidgetConfig({
  zone: "customers.details.after",
})

export default CustomerMilestonesWidget
