import React from "react";
import { useParams } from "react-router-dom";
import { useAdminProduct } from "@medusajs/admin-sdk";
import { ProductDetailsWidget } from "../../../widgets";

const ProductDetailsPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const { product, isLoading, error } = useAdminProduct(id!);

  if (isLoading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="text-gray-500">Loading product details...</div>
      </div>
    );
  }

  if (error || !product) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="text-red-500">Error loading product details</div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <ProductDetailsWidget product={product} />
    </div>
  );
};

export default ProductDetailsPage;





