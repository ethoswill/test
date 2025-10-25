import { defineWidgetConfig } from "@medusajs/admin-sdk"
import { useState, useEffect } from "react"

interface ProductMetadata {
  [key: string]: string | number | boolean
}

const ProductMetadataWidget = () => {
  const [metadata, setMetadata] = useState<ProductMetadata>({})
  const [newKey, setNewKey] = useState("")
  const [newValue, setNewValue] = useState("")
  const [loading, setLoading] = useState(true)

  // Predefined customization methods
  const customizationMethods = [
    "Screen Printing",
    "Heat Transfer",
    "Vinyl Cutting",
    "Laser Engraving",
    "Custom Stitching",
    "Digital Printing",
    "Sublimation",
    "Hand Painting",
    "Custom Patches"
  ]

  // Get product ID from URL or context
  const productId = window.location.pathname.split('/').pop()

  useEffect(() => {
    if (productId) {
      fetchProductMetadata()
    }
  }, [productId])

  const fetchProductMetadata = async () => {
    try {
      const response = await fetch(`/admin/products/${productId}`)
      const data = await response.json()
      setMetadata(data.product?.metadata || {})
    } catch (error) {
      console.error("Error fetching product metadata:", error)
    } finally {
      setLoading(false)
    }
  }

  const handleAddMetadata = async () => {
    if (!newKey.trim() || !newValue.trim()) return

    try {
      const updatedMetadata = {
        ...metadata,
        [newKey]: newValue
      }

      const response = await fetch(`/admin/products/${productId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          metadata: updatedMetadata
        }),
      })
      
      if (response.ok) {
        setMetadata(updatedMetadata)
        setNewKey("")
        setNewValue("")
      }
    } catch (error) {
      console.error("Error updating product metadata:", error)
    }
  }

  const handleUpdateMetadata = async (key: string, value: string) => {
    try {
      const updatedMetadata = {
        ...metadata,
        [key]: value
      }

      const response = await fetch(`/admin/products/${productId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          metadata: updatedMetadata
        }),
      })
      
      if (response.ok) {
        setMetadata(updatedMetadata)
      }
    } catch (error) {
      console.error("Error updating product metadata:", error)
    }
  }

  const handleDeleteMetadata = async (key: string) => {
    try {
      const updatedMetadata = { ...metadata }
      delete updatedMetadata[key]

      const response = await fetch(`/admin/products/${productId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          metadata: updatedMetadata
        }),
      })
      
      if (response.ok) {
        setMetadata(updatedMetadata)
      }
    } catch (error) {
      console.error("Error updating product metadata:", error)
    }
  }

  if (loading) {
    return <div>Loading product attributes...</div>
  }

  return (
    <div className="bg-white p-6 rounded-lg shadow-sm border">
      <div className="flex justify-between items-center mb-4">
        <h3 className="text-lg font-semibold">Product Attributes (Metadata)</h3>
      </div>

      {/* Add new attribute */}
      <div className="mb-6 p-4 border rounded-lg bg-gray-50">
        <h4 className="font-medium mb-3">Add New Attribute</h4>
        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="block text-sm font-medium mb-1">Attribute Name</label>
            <input
              type="text"
              value={newKey}
              onChange={(e) => setNewKey(e.target.value)}
              className="w-full border rounded px-3 py-2"
              placeholder="e.g., color, size, material, customization_method"
            />
          </div>
          <div>
            <label className="block text-sm font-medium mb-1">Attribute Value</label>
            {newKey.toLowerCase() === 'customization_method' || newKey.toLowerCase() === 'customization method' ? (
              <select
                value={newValue}
                onChange={(e) => setNewValue(e.target.value)}
                className="w-full border rounded px-3 py-2"
              >
                <option value="">Select customization method...</option>
                {customizationMethods.map((method) => (
                  <option key={method} value={method}>{method}</option>
                ))}
              </select>
            ) : (
              <input
                type="text"
                value={newValue}
                onChange={(e) => setNewValue(e.target.value)}
                className="w-full border rounded px-3 py-2"
                placeholder="e.g., Red, Large, Cotton"
              />
            )}
          </div>
        </div>
        <button
          onClick={handleAddMetadata}
          className="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Add Attribute
        </button>
      </div>

      {/* Display existing attributes */}
      <div className="space-y-3">
        {Object.keys(metadata).length === 0 ? (
          <p className="text-gray-500">No attributes defined for this product.</p>
        ) : (
          Object.entries(metadata).map(([key, value]) => (
            <div key={key} className="flex justify-between items-center p-3 border rounded">
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <span className="font-medium capitalize">{key.replace(/_/g, ' ')}</span>
                  {key.toLowerCase() === 'customization_method' && (
                    <span className="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded">Customization</span>
                  )}
                </div>
                <div className="text-sm text-gray-600 mt-1">
                  {key.toLowerCase() === 'customization_method' ? (
                    <select
                      value={String(value)}
                      onChange={(e) => handleUpdateMetadata(key, e.target.value)}
                      className="w-full border rounded px-2 py-1 text-sm"
                    >
                      <option value="">Select customization method...</option>
                      {customizationMethods.map((method) => (
                        <option key={method} value={method}>{method}</option>
                      ))}
                    </select>
                  ) : (
                    <input
                      type="text"
                      value={String(value)}
                      onChange={(e) => handleUpdateMetadata(key, e.target.value)}
                      className="w-full border rounded px-2 py-1 text-sm"
                    />
                  )}
                </div>
              </div>
              <button
                onClick={() => handleDeleteMetadata(key)}
                className="text-red-600 hover:text-red-800 ml-4"
              >
                Delete
              </button>
            </div>
          ))
        )}
      </div>

      {/* Info about metadata */}
      <div className="mt-6 p-4 bg-blue-50 rounded-lg">
        <h4 className="font-medium text-blue-900 mb-2">About Product Attributes</h4>
        <p className="text-sm text-blue-800">
          Product attributes are stored as metadata and can be used for filtering, 
          searching, and displaying additional product information. These attributes 
          will also be included in product exports and API responses.
        </p>
      </div>
    </div>
  )
}

export const config = defineWidgetConfig({
  zone: "product.details.after",
})

export default ProductMetadataWidget
