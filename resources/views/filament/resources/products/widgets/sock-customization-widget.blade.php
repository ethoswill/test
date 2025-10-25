@if($this->shouldShowWidget())
    <x-filament-widgets::widget>
        <x-filament::section>
            <x-slot name="heading">
                Sock Customization Details
            </x-slot>

            <!-- Debug info -->
            <div class="mb-4 p-2 bg-yellow-100 border border-yellow-300 rounded text-xs">
                <strong>Debug:</strong> Thread Color: "{{ $thread_color }}", Logo Style: "{{ $logo_style }}"
            </div>

            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Thread Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thread Color</label>
                            <input 
                                type="text" 
                                wire:model="thread_color"
                                value="{{ $thread_color }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Thread Color(s) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Thread Color(s)</label>
                            <input 
                                type="text" 
                                wire:model="thread_colors"
                                value="{{ $thread_colors }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Grip Design -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grip Design</label>
                            <input 
                                type="file" 
                                wire:model.defer="grip_design_file"
                                accept="image/*,application/pdf"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Packaging Design -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Packaging Design</label>
                            <input 
                                type="file" 
                                wire:model.defer="packaging_design_file"
                                accept="image/*,application/pdf"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Logo Style -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Logo Style</label>
                            <select 
                                wire:model="logo_style"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="Embroidered" {{ $logo_style == 'Embroidered' ? 'selected' : '' }}>Embroidered</option>
                                <option value="Printed" {{ $logo_style == 'Printed' ? 'selected' : '' }}>Printed</option>
                                <option value="Heat Transfer" {{ $logo_style == 'Heat Transfer' ? 'selected' : '' }}>Heat Transfer</option>
                                <option value="Patch" {{ $logo_style == 'Patch' ? 'selected' : '' }}>Patch</option>
                            </select>
                        </div>

                        <!-- Embroidered Logo Thread Color(s) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Embroidered Logo Thread Color(s)</label>
                            <input 
                                type="text" 
                                wire:model="embroidered_logo_thread_colors"
                                value="{{ $embroidered_logo_thread_colors }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Grip Color -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grip Color</label>
                            <input 
                                type="text" 
                                wire:model="grip_color"
                                value="{{ $grip_color }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>
                
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save Changes
                        </button>
                    </div>
            </form>
        </x-filament::section>
    </x-filament-widgets::widget>
@endif
