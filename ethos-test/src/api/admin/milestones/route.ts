import { MedusaRequest, MedusaResponse } from "@medusajs/framework/http";

export async function GET(
  req: MedusaRequest,
  res: MedusaResponse
) {
  // Redirect to the milestones page
  res.redirect('/app/customer-milestones');
}


