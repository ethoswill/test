<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Product Details
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->name ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->website_url ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">HS Code</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->hs_code ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Product</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->parent_product ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->status ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->supplier->name ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Type</label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm text-gray-900">
                        {{ $record->product_type ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>





