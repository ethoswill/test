import { MedusaRequest, MedusaResponse } from "@medusajs/framework/http"
import { createProductsWorkflow } from "@medusajs/medusa/core-flows"
import { ProductStatus } from "@medusajs/framework/types"

interface LaravelProduct {
  id: number
  title: string
  description?: string
  handle: string
  status: 'published' | 'draft'
  price: number
  currency: string
  weight?: number
  length?: number
  width?: number
  height?: number
  hs_code?: string
  mid_code?: string
  origin_country?: string
  material?: string
  images?: Array<{ url: string; alt?: string }>
  variants?: Array<{
    title: string
    sku: string
    price: number
    currency: string
    weight?: number
    options?: Record<string, string>
  }>
  categories?: Array<{ id: string; name: string }>
  tags?: Array<{ id: string; value: string }>
  metadata?: Record<string, any>
}

export async function POST(
  req: MedusaRequest,
  res: MedusaResponse
) {
  try {
    const laravelProduct: LaravelProduct = req.body
    
    // Validate required fields
    if (!laravelProduct.title || !laravelProduct.handle) {
      return res.status(400).json({ 
        error: "Missing required fields: title and handle are required" 
      })
    }

    // Transform Laravel product to Medusa format
    const medusaProduct = {
      title: laravelProduct.title,
      description: laravelProduct.description || "",
      handle: laravelProduct.handle,
      status: laravelProduct.status === 'published' ? ProductStatus.PUBLISHED : ProductStatus.DRAFT,
      weight: laravelProduct.weight,
      length: laravelProduct.length,
      width: laravelProduct.width,
      height: laravelProduct.height,
      hs_code: laravelProduct.hs_code,
      mid_code: laravelProduct.mid_code,
      origin_country: laravelProduct.origin_country,
      material: laravelProduct.material,
      images: laravelProduct.images?.map(img => ({
        url: img.url,
        alt_text: img.alt || ""
      })) || [],
      variants: laravelProduct.variants?.map(variant => ({
        title: variant.title,
        sku: variant.sku,
        prices: [{
          amount: variant.price,
          currency_code: variant.currency
        }],
        weight: variant.weight,
        options: variant.options || {}
      })) || [{
        title: "Default",
        sku: laravelProduct.handle + "-default",
        prices: [{
          amount: laravelProduct.price,
          currency_code: laravelProduct.currency
        }]
      }],
      metadata: {
        ...laravelProduct.metadata,
        laravel_id: laravelProduct.id,
        sync_date: new Date().toISOString()
      }
    }

    // Create product in Medusa
    const { result } = await createProductsWorkflow(req.scope).run({
      input: {
        products: [medusaProduct]
      }
    })

    const createdProduct = result[0]

    res.json({
      success: true,
      message: "Product synced successfully",
      medusa_id: createdProduct.id,
      laravel_id: laravelProduct.id,
      handle: createdProduct.handle
    })

  } catch (error) {
    console.error("Error syncing product:", error)
    res.status(500).json({ 
      error: "Failed to sync product",
      details: error.message 
    })
  }
}

export async function PUT(
  req: MedusaRequest,
  res: MedusaResponse
) {
  try {
    const { laravel_id } = req.body
    const productService = req.scope.resolve("productService")
    
    // Find product by Laravel ID in metadata
    const products = await productService.list({
      metadata: { laravel_id }
    })

    if (products.length === 0) {
      return res.status(404).json({ error: "Product not found" })
    }

    const product = products[0]
    const laravelProduct: LaravelProduct = req.body

    // Update product
    const updatedProduct = await productService.update(product.id, {
      title: laravelProduct.title,
      description: laravelProduct.description || "",
      handle: laravelProduct.handle,
      status: laravelProduct.status === 'published' ? ProductStatus.PUBLISHED : ProductStatus.DRAFT,
      weight: laravelProduct.weight,
      length: laravelProduct.length,
      width: laravelProduct.width,
      height: laravelProduct.height,
      hs_code: laravelProduct.hs_code,
      mid_code: laravelProduct.mid_code,
      origin_country: laravelProduct.origin_country,
      material: laravelProduct.material,
      metadata: {
        ...product.metadata,
        ...laravelProduct.metadata,
        laravel_id: laravelProduct.id,
        sync_date: new Date().toISOString()
      }
    })

    res.json({
      success: true,
      message: "Product updated successfully",
      medusa_id: updatedProduct.id,
      laravel_id: laravelProduct.id
    })

  } catch (error) {
    console.error("Error updating product:", error)
    res.status(500).json({ 
      error: "Failed to update product",
      details: error.message 
    })
  }
}

export async function DELETE(
  req: MedusaRequest,
  res: MedusaResponse
) {
  try {
    const { laravel_id } = req.body
    const productService = req.scope.resolve("productService")
    
    // Find product by Laravel ID in metadata
    const products = await productService.list({
      metadata: { laravel_id }
    })

    if (products.length === 0) {
      return res.status(404).json({ error: "Product not found" })
    }

    const product = products[0]

    // Delete product
    await productService.delete(product.id)

    res.json({
      success: true,
      message: "Product deleted successfully",
      laravel_id: laravel_id
    })

  } catch (error) {
    console.error("Error deleting product:", error)
    res.status(500).json({ 
      error: "Failed to delete product",
      details: error.message 
    })
  }
}

