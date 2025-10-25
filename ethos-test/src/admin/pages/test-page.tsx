import { definePageConfig } from "@medusajs/admin-sdk"

const TestPage = () => {
  return (
    <div style={{ padding: '20px', backgroundColor: 'white' }}>
      <h1 style={{ color: 'black', fontSize: '24px', marginBottom: '20px' }}>
        Test Page
      </h1>
      <p style={{ color: 'gray', fontSize: '16px' }}>
        This is a simple test page to verify that custom pages are working.
      </p>
      <div style={{ 
        marginTop: '20px', 
        padding: '15px', 
        backgroundColor: '#f0f9ff', 
        border: '1px solid #0ea5e9',
        borderRadius: '8px'
      }}>
        <p style={{ color: '#0c4a6e', fontWeight: 'bold' }}>
          ✅ If you can see this, custom pages are working!
        </p>
      </div>
    </div>
  )
}

export const config = definePageConfig({
  label: "Test Page",
  icon: "check-circle",
})

export default TestPage


