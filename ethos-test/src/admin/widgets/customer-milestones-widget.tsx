import { defineWidgetConfig } from "@medusajs/admin-sdk"
import { useState, useEffect } from "react"

const CustomerClassProgressWidget = () => {
  const [customerProgress, setCustomerProgress] = useState(null)
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchCustomerProgress()
  }, [])

  const fetchCustomerProgress = async () => {
    try {
      setLoading(true)
      
      // Get current customer ID from URL
      const pathParts = window.location.pathname.split('/')
      const customerId = pathParts[pathParts.length - 1]
      
      if (!customerId || customerId === 'customers') {
        setLoading(false)
        return
      }

      // Fetch customer data
      const response = await fetch(`/admin/customers/${customerId}`)
      const data = await response.json()
      
      if (data.customer) {
        // Mock workout class data with realistic patterns
        const mockProgress = generateWorkoutProgress(data.customer)
        setCustomerProgress(mockProgress)
      }
    } catch (error) {
      console.error("Error fetching customer progress:", error)
    } finally {
      setLoading(false)
    }
  }

  const generateWorkoutProgress = (customer) => {
    // Generate realistic workout class data
    const totalClasses = Math.floor(Math.random() * 150) + 20 // 20-170 classes
    const classesCompleted = Math.floor(Math.random() * totalClasses) + 1
    
    // Calculate average classes per week based on customer history
    const weeksActive = Math.max(1, Math.floor(Math.random() * 52) + 4) // 4-56 weeks
    const avgClassesPerWeek = Math.round((classesCompleted / weeksActive) * 10) / 10
    
    // Calculate projected milestone dates
    const milestones = calculateMilestoneProjections(classesCompleted, avgClassesPerWeek)
    
    return {
      id: customer.id,
      customerId: customer.id,
      customerName: `${customer.first_name || ''} ${customer.last_name || ''}`.trim() || 'Unknown',
      classesCompleted: classesCompleted,
      totalClasses: totalClasses,
      avgClassesPerWeek: avgClassesPerWeek,
      weeksActive: weeksActive,
      progressPercentage: Math.round((classesCompleted / totalClasses) * 100),
      milestones: milestones,
      lastClassDate: customer.created_at,
      nextClassProjection: calculateNextClassDate(avgClassesPerWeek)
    }
  }

  const calculateMilestoneProjections = (currentClasses, avgClassesPerWeek) => {
    const milestones = [100, 200, 500, 750, 1000]
    const projections = []
    
    milestones.forEach(target => {
      if (currentClasses < target) {
        const classesNeeded = target - currentClasses
        const weeksToMilestone = Math.ceil(classesNeeded / avgClassesPerWeek)
        const projectedDate = new Date()
        projectedDate.setDate(projectedDate.getDate() + (weeksToMilestone * 7))
        
        projections.push({
          target: target,
          classesNeeded: classesNeeded,
          weeksToMilestone: weeksToMilestone,
          projectedDate: projectedDate,
          isAchievable: weeksToMilestone <= 104 // Within 2 years
        })
      }
    })
    
    return projections
  }

  const calculateNextClassDate = (avgClassesPerWeek) => {
    const daysBetweenClasses = Math.round(7 / avgClassesPerWeek)
    const nextClass = new Date()
    nextClass.setDate(nextClass.getDate() + daysBetweenClasses)
    return nextClass
  }

  const formatDate = (date) => {
    return date.toLocaleDateString('en-US', { 
      month: 'short', 
      day: 'numeric', 
      year: 'numeric' 
    })
  }

  const handleOrderMerchReward = (milestone) => {
    // Get customer info for the order
    const customerName = customerProgress?.customerName || 'Customer'
    const milestoneTarget = milestone.target
    
    // Create a simple alert for now - this could be replaced with a modal or redirect
    alert(`Ordering merch reward for ${customerName}!\n\nMilestone: ${milestoneTarget} Classes\nProjected Date: ${formatDate(milestone.projectedDate)}\n\nThis would typically:\n- Create a special order\n- Apply milestone discount\n- Send notification to customer`)
    
    // In a real implementation, this would:
    // 1. Create an order in Medusa
    // 2. Apply milestone-specific discounts
    // 3. Send notification to customer
    // 4. Track the reward in customer metadata
    console.log('Ordering merch reward for milestone:', milestone)
  }

  return (
    <div className="bg-white rounded-lg border border-gray-200 p-6">
      <div className="flex items-center justify-between mb-6">
        <h3 className="text-lg font-semibold text-gray-900">Class Progress</h3>
        <button
          onClick={fetchCustomerProgress}
          className="text-sm text-gray-500 hover:text-gray-700 px-3 py-1 rounded border border-gray-300 hover:bg-gray-50"
        >
          Refresh
        </button>
      </div>
      
      {loading ? (
        <div className="flex items-center justify-center py-8">
          <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-600"></div>
          <span className="ml-2 text-sm text-gray-500">Loading...</span>
        </div>
      ) : !customerProgress ? (
        <div className="text-center py-8 text-gray-500">
          <p className="text-sm">No customer selected or progress data unavailable.</p>
        </div>
      ) : (
        <div className="space-y-6">
          {/* Customer Info */}
          <div className="flex items-center space-x-4">
            <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
              <span className="text-sm font-medium text-gray-600">
                {customerProgress.customerName.charAt(0)}
              </span>
            </div>
            <div>
              <p className="font-medium text-gray-900">{customerProgress.customerName}</p>
              <p className="text-sm text-gray-500">
                {customerProgress.weeksActive} weeks active • {customerProgress.avgClassesPerWeek} classes/week avg
              </p>
            </div>
          </div>

            {/* Progress Bar */}
            <div>
              <div className="flex justify-between text-sm mb-2">
                <span className="text-gray-600">Classes Completed</span>
                <span className="font-medium text-gray-900">
                  {customerProgress.classesCompleted} classes
                </span>
              </div>
              
              <div className="w-full bg-gray-200 rounded-full h-2">
                <div 
                  className="h-2 rounded-full transition-all duration-300"
                  style={{ 
                    width: `${customerProgress.progressPercentage}%`,
                    background: `linear-gradient(90deg, 
                      rgba(59, 130, 246, ${Math.max(0.1, customerProgress.progressPercentage / 100)}) 0%, 
                      rgba(59, 130, 246, 1) 100%)`
                  }}
                ></div>
              </div>
              
              <div className="text-right mt-1">
                <span className="text-sm font-medium text-gray-600">
                  {customerProgress.progressPercentage}%
                </span>
              </div>
            </div>

          {/* Milestone Projections */}
          <div>
            <h4 className="text-sm font-medium text-gray-900 mb-3">Milestone Projections</h4>
            <div className="space-y-2">
              {customerProgress.milestones.slice(0, 3).map((milestone) => (
                <div key={milestone.target} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                  <div className="flex-1">
                    <div className="flex items-center gap-4">
                      <span className="text-sm font-medium text-gray-900">
                        {milestone.target} Classes
                      </span>
                      <span className="text-xs text-gray-500">
                        {milestone.classesNeeded} classes remaining
                      </span>
                      <span className="text-xs text-gray-500">
                        Projected to hit by {formatDate(milestone.projectedDate)}
                      </span>
                    </div>
                  </div>
                  <button
                    onClick={() => handleOrderMerchReward(milestone)}
                    className="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors duration-200"
                  >
                    Order Merch Reward
                  </button>
                </div>
              ))}
            </div>
          </div>

          {/* Stats Grid */}
          <div className="grid grid-cols-2 gap-4">
            <div className="p-4 bg-gray-50 rounded-lg">
              <p className="text-xs text-gray-500 mb-1">Next Class</p>
              <p className="text-sm font-medium text-gray-900">
                ~{formatDate(customerProgress.nextClassProjection)}
              </p>
            </div>
            <div className="p-4 bg-gray-50 rounded-lg">
              <p className="text-xs text-gray-500 mb-1">Classes This Week</p>
              <p className="text-sm font-medium text-gray-900">
                {customerProgress.avgClassesPerWeek} classes
              </p>
            </div>
          </div>
        </div>
      )}
      
      <div className="mt-6 pt-4 border-t border-gray-200">
        <button
          onClick={() => window.open('/app/customer-milestones', '_blank')}
          className="w-full text-center text-sm text-blue-600 hover:text-blue-800 py-2"
        >
          View Upcoming Customer Milestones →
        </button>
      </div>
    </div>
  )
}

export const config = defineWidgetConfig({
  zone: "customer.details.after",
})

export default CustomerClassProgressWidget
