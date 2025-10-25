<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MedusaCustomerSyncService
{
    protected $medusaConnection;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->medusaConnection = 'medusa';
        $this->baseUrl = config('medusa.base_url');
        $this->apiKey = config('medusa.api_key');
    }

    /**
     * Sync all customers from Medusa to Laravel
     */
    public function syncAllCustomers(): array
    {
        try {
            $customers = $this->fetchCustomersFromMedusa();
            return $this->syncCustomers($customers);
        } catch (\Exception $e) {
            Log::error('Failed to sync customers from Medusa', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to sync customers: ' . $e->getMessage(),
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'errors' => 0
            ];
        }
    }

    /**
     * Sync a single customer from Medusa to Laravel
     */
    public function syncCustomer(string $medusaCustomerId): array
    {
        try {
            $customer = $this->fetchCustomerFromMedusa($medusaCustomerId);
            if (!$customer) {
                return [
                    'success' => false,
                    'message' => 'Customer not found in Medusa',
                    'medusa_id' => $medusaCustomerId
                ];
            }

            $result = $this->createOrUpdateCustomer($customer);
            return [
                'success' => true,
                'message' => "Customer '{$customer['email']}' synced successfully",
                'medusa_id' => $medusaCustomerId,
                'laravel_id' => $result['id'],
                'action' => $result['action']
            ];
        } catch (\Exception $e) {
            Log::error('Failed to sync customer from Medusa', [
                'medusa_id' => $medusaCustomerId,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to sync customer: ' . $e->getMessage(),
                'medusa_id' => $medusaCustomerId
            ];
        }
    }

    /**
     * Fetch all customers from Medusa database
     */
    protected function fetchCustomersFromMedusa(): array
    {
        try {
            $customers = DB::connection($this->medusaConnection)
                ->table('customer')
                ->orderBy('created_at', 'desc')
                ->get();

            return $customers->map(function ($customer) {
                return $this->formatCustomerFromMedusa((array) $customer);
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Failed to fetch customers from Medusa database', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Fetch a single customer from Medusa database
     */
    protected function fetchCustomerFromMedusa(string $medusaId): ?array
    {
        try {
            $customer = DB::connection($this->medusaConnection)
                ->table('customer')
                ->where('id', $medusaId)
                ->first();

            return $customer ? $this->formatCustomerFromMedusa((array) $customer) : null;
        } catch (\Exception $e) {
            Log::error('Failed to fetch customer from Medusa database', [
                'medusa_id' => $medusaId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Format customer data from Medusa format to Laravel format
     */
    protected function formatCustomerFromMedusa(array $medusaCustomer): array
    {
        $metadata = [];
        if (!empty($medusaCustomer['metadata'])) {
            $metadata = is_string($medusaCustomer['metadata']) 
                ? json_decode($medusaCustomer['metadata'], true) 
                : $medusaCustomer['metadata'];
        }

        return [
            'medusa_id' => $medusaCustomer['id'],
            'email' => $medusaCustomer['email'],
            'first_name' => $medusaCustomer['first_name'],
            'last_name' => $medusaCustomer['last_name'],
            'phone' => $medusaCustomer['phone'],
            'date_of_birth' => $metadata['date_of_birth'] ?? null,
            'gender' => $metadata['gender'] ?? null,
            'metadata' => $metadata,
            'has_account' => $medusaCustomer['has_account'] ?? false,
            'last_login_at' => $metadata['last_login_at'] ?? null,
            'synced_at' => now(),
        ];
    }

    /**
     * Sync multiple customers
     */
    protected function syncCustomers(array $customers): array
    {
        $total = count($customers);
        $created = 0;
        $updated = 0;
        $errors = 0;
        $errorMessages = [];

        foreach ($customers as $customerData) {
            try {
                $result = $this->createOrUpdateCustomer($customerData);
                if ($result['action'] === 'created') {
                    $created++;
                } else {
                    $updated++;
                }
            } catch (\Exception $e) {
                $errors++;
                $errorMessages[] = "Failed to sync customer {$customerData['email']}: " . $e->getMessage();
                Log::error('Failed to sync customer', [
                    'email' => $customerData['email'],
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'success' => $errors === 0,
            'message' => "Synced {$total} customers: {$created} created, {$updated} updated, {$errors} errors",
            'total' => $total,
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
            'error_messages' => $errorMessages
        ];
    }

    /**
     * Create or update a customer in Laravel
     */
    protected function createOrUpdateCustomer(array $customerData): array
    {
        $existingCustomer = Customer::byMedusaId($customerData['medusa_id'])->first();

        if ($existingCustomer) {
            $existingCustomer->update($customerData);
            return [
                'id' => $existingCustomer->id,
                'action' => 'updated'
            ];
        } else {
            $customer = Customer::create($customerData);
            return [
                'id' => $customer->id,
                'action' => 'created'
            ];
        }
    }

    /**
     * Test connection to Medusa
     */
    public function testConnection(): array
    {
        try {
            $customerCount = DB::connection($this->medusaConnection)
                ->table('customer')
                ->count();

            return [
                'success' => true,
                'message' => "Connection to Medusa successful. Found {$customerCount} customers.",
                'customer_count' => $customerCount
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats(): array
    {
        $totalCustomers = Customer::count();
        $syncedCustomers = Customer::whereNotNull('synced_at')->count();
        $recentSyncs = Customer::where('synced_at', '>=', now()->subHours(24))->count();

        return [
            'total_customers' => $totalCustomers,
            'synced_customers' => $syncedCustomers,
            'recent_syncs_24h' => $recentSyncs,
            'last_sync' => Customer::latest('synced_at')->value('synced_at')
        ];
    }
}
