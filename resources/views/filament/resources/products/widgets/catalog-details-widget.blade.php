<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Catalog Website Details
        </x-slot>

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <!-- Minimums -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimums</label>
                        <input 
                            type="text" 
                            wire:model="minimums"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Starting From Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Starting From Price</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input 
                                type="text" 
                                wire:model="starting_from_price"
                                class="w-full border border-gray-300 rounded-md pl-7 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <!-- Fabric -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fabric</label>
                        <input 
                            type="text" 
                            wire:model="fabric"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- How It Fits -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">How It Fits</label>
                        <input 
                            type="text" 
                            wire:model="how_it_fits"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Care -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Care</label>
                        <textarea 
                            wire:model="care_instructions"
                            rows="2"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <!-- Lead Times -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lead Times</label>
                        <input 
                            type="text" 
                            wire:model="lead_times"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Available Sizes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Sizes</label>
                        <input 
                            type="text" 
                            wire:model="available_sizes"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Available Customization Methods -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Customization Methods</label>
                        <input 
                            type="text" 
                            wire:model="customization_methods"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="text-xs text-gray-500 mt-1">Separate multiple methods with commas</p>
                    </div>

                    <!-- Model Size -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model Size</label>
                        <input 
                            type="text" 
                            wire:model="model_size"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Save Changes
                </button>
            </div>
        </form>
    </x-filament::section>
</x-filament-widgets::widget>
