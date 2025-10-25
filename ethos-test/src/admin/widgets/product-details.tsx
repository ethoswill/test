import React, { useState, useEffect } from "react";
import { defineWidgetConfig, useAdminProduct } from "@medusajs/admin-sdk";

const ProductDetailsWidget: React.FC = () => {
  const [product, setProduct] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  // Get product ID from URL
  const productId = window.location.pathname.split('/').pop();

  useEffect(() => {
    if (productId) {
      fetchProduct();
    }
  }, [productId]);

  const fetchProduct = async () => {
    try {
      const response = await fetch(`/admin/products/${productId}`);
      const data = await response.json();
      setProduct(data.product);
    } catch (error) {
      console.error("Error fetching product:", error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="bg-white p-6 rounded-lg shadow-sm border">
        <div className="text-center text-gray-500">Loading product details...</div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="bg-white p-6 rounded-lg shadow-sm border">
        <div className="text-center text-red-500">Error loading product details</div>
      </div>
    );
  }

  const metadata = product.metadata || {};
  
  const getStatusColor = (status: string) => {
    switch (status) {
      case 'published': return 'green';
      case 'draft': return 'orange';
      default: return 'grey';
    }
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD'
    }).format(price);
  };

  return (
    <div className="bg-white p-6 rounded-lg shadow-sm border">
      <div className="space-y-6">
        <div>
          <h2 className="text-lg font-semibold mb-2 text-gray-900">
            📋 Product Details from Filament
          </h2>
          <p className="text-sm text-gray-600">
            Synced data from Laravel/Filament application
          </p>
        </div>

        <hr className="border-gray-200" />

        {/* Basic Information */}
        <div>
          <h3 className="text-md font-medium mb-3 text-gray-900">Basic Information</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <p className="text-sm font-medium text-gray-700">Laravel ID</p>
              <p className="text-sm text-gray-900">{metadata.laravel_id || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Supplier Code</p>
              <p className="text-sm text-gray-900">{metadata.supplier_code || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Product Type</p>
              <p className="text-sm text-gray-900">{metadata.product_type || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Parent Product</p>
              <p className="text-sm text-gray-900">{metadata.parent_product || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">HS Code</p>
              <p className="text-sm text-gray-900">{metadata.hs_code || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Website URL</p>
              <p className="text-sm text-gray-900">
                {metadata.website_url ? (
                  <a href={metadata.website_url} target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:underline">
                    {metadata.website_url}
                  </a>
                ) : 'N/A'}
              </p>
            </div>
          </div>
        </div>

        <hr className="border-gray-200" />

        {/* Pricing Information */}
        <div>
          <h3 className="text-md font-medium mb-3 text-gray-900">💰 Pricing Information</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <p className="text-sm font-medium text-gray-700">Ethos Cost Price</p>
              <p className="text-sm font-semibold text-green-600">
                {metadata.ethos_cost_price ? formatPrice(metadata.ethos_cost_price) : 'N/A'}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Customer B2B Price</p>
              <p className="text-sm font-semibold text-blue-600">
                {metadata.customer_b2b_price ? formatPrice(metadata.customer_b2b_price) : 'N/A'}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Customer DTC Price</p>
              <p className="text-sm font-semibold text-purple-600">
                {metadata.customer_dtc_price ? formatPrice(metadata.customer_dtc_price) : 'N/A'}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Franchisee Price</p>
              <p className="text-sm font-semibold text-orange-600">
                {metadata.franchisee_price ? formatPrice(metadata.franchisee_price) : 'N/A'}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Starting From Price</p>
              <p className="text-sm text-gray-900">
                {metadata.starting_from_price ? formatPrice(metadata.starting_from_price) : 'N/A'}
              </p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Minimum Order Quantity</p>
              <p className="text-sm text-gray-900">{metadata.minimum_order_quantity || 'N/A'}</p>
            </div>
          </div>
        </div>

        <hr className="border-gray-200" />

        {/* Product Specifications */}
        <div>
          <h3 className="text-md font-medium mb-3 text-gray-900">🔧 Product Specifications</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <p className="text-sm font-medium text-gray-700">Fabric</p>
              <p className="text-sm text-gray-900">{metadata.fabric || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Model Size</p>
              <p className="text-sm text-gray-900">{metadata.model_size || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">How It Fits</p>
              <p className="text-sm text-gray-900">{metadata.how_it_fits || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Available Sizes</p>
              <p className="text-sm text-gray-900">{metadata.available_sizes || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Customization Methods</p>
              <p className="text-sm text-gray-900">{metadata.customization_methods || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Care Instructions</p>
              <p className="text-sm text-gray-900">{metadata.care_instructions || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Lead Times</p>
              <p className="text-sm text-gray-900">{metadata.lead_times || 'N/A'}</p>
            </div>
            <div>
              <p className="text-sm font-medium text-gray-700">Minimums</p>
              <p className="text-sm text-gray-900">{metadata.minimums || 'N/A'}</p>
            </div>
          </div>
        </div>

        <hr className="border-gray-200" />

        {/* Additional Settings */}
        <div>
          <h3 className="text-md font-medium mb-3 text-gray-900">⚙️ Additional Settings</h3>
          <div className="grid grid-cols-2 gap-4">
            <div>
              <p className="text-sm font-medium text-gray-700">Split Across Variants</p>
              <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                metadata.split_across_variants 
                  ? 'bg-green-100 text-green-800' 
                  : 'bg-gray-100 text-gray-800'
              }`}>
                {metadata.split_across_variants ? 'Yes' : 'No'}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export const config = defineWidgetConfig({
  zone: "product.details.after",
});

export default ProductDetailsWidget;
