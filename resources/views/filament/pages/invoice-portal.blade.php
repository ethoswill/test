<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Invoice Management Portal</h2>
            <p class="text-gray-600 mb-6">Create and manage invoices for your customers and vendors.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="font-medium text-gray-900 mb-2">Create Invoice</h3>
                    <p class="text-sm text-gray-600 mb-4">Create a new invoice for customers or vendors</p>
                    <a href="{{ route('filament.admin.resources.invoices.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create Invoice
                    </a>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="font-medium text-gray-900 mb-2">View Invoices</h3>
                    <p class="text-sm text-gray-600 mb-4">View and manage all invoices</p>
                    <a href="{{ route('filament.admin.resources.invoices.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        View All Invoices
                    </a>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="font-medium text-gray-900 mb-2">Overdue Invoices</h3>
                    <p class="text-sm text-gray-600 mb-4">Track overdue invoices and payments</p>
                    <a href="{{ route('filament.admin.pages.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        View Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        @php
            $stats = $this->getInvoiceStats();
        @endphp
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_invoices'] }}</div>
                    <div class="text-sm text-gray-600">Total Invoices</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['sent_invoices'] }}</div>
                    <div class="text-sm text-gray-600">Sent Invoices</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['paid_invoices'] }}</div>
                    <div class="text-sm text-gray-600">Paid Invoices</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['overdue_invoices'] }}</div>
                    <div class="text-sm text-gray-600">Overdue Invoices</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>