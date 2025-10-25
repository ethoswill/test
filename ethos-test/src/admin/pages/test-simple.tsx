import { definePageConfig } from "@medusajs/admin-sdk"

const TestPage = () => {
  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold">Test Page</h1>
      <p>This is a simple test page to verify page routing works.</p>
    </div>
  )
}

export const config = definePageConfig({
  label: "Test Page",
  icon: "cog",
})

export default TestPage


