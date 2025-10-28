<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span>Customization Methods</span>
                <x-filament::button wire:click="toggleEdit" size="sm" color="primary">
                    {{ $this->showForm ? 'Cancel Edit' : 'Edit' }}
                </x-filament::button>
            </div>
        </x-slot>

        <div class="space-y-8">
            @foreach($this->methods as $methodKey => $method)
                <div class="grid grid-cols-12 gap-6 p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                    @if($this->showForm)
                        <div class="col-span-12 flex justify-end">
                            <x-filament::button wire:click="saveMethod('{{ $methodKey }}')" color="success" size="sm">
                                Save {{ $method['display_name'] }}
                            </x-filament::button>
                        </div>
                    @endif
                    <!-- Left Side: Image (25%) -->
                    <div class="col-span-12 lg:col-span-3 space-y-4">
                        @if($this->showForm)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Method Name
                                </label>
                                <input 
                                    type="text" 
                                    wire:model.live="methods.{{ $methodKey }}.display_name"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-lg font-semibold"
                                    placeholder="Enter method name..."
                                />
                            </div>
                        @else
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $method['display_name'] }}</h3>
                        @endif
                        
                        @if($this->showForm)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Add Image from URL
                                </label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        wire:model.live="methods.{{ $methodKey }}.new_image_url"
                                        placeholder="https://example.com/image.jpg"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    />
                                    <x-filament::button wire:click="addImage('{{ $methodKey }}')" color="primary" size="sm">
                                        Add
                                    </x-filament::button>
                                </div>
                            </div>

                            @if(!empty($method['image_urls']))
                                <div class="space-y-3 max-w-[150px]">
                                    @foreach($method['image_urls'] as $index => $imageUrl)
                                        <div class="relative group border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                                            <img src="{{ $imageUrl }}" 
                                                 alt="Image {{ $index + 1 }}"
                                                 class="w-full aspect-[4/5] object-cover">
                                            <button 
                                                wire:click="removeImage('{{ $methodKey }}', {{ $index }})"
                                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-opacity"
                                                title="Remove image">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            @if(!empty($method['image_urls']) && isset($method['image_urls'][0]))
                                <div class="max-w-[150px] mx-auto lg:mx-0">
                                    <img src="{{ $method['image_urls'][0] }}" 
                                         alt="Image"
                                         class="w-full aspect-[4/5] object-cover rounded-lg border border-gray-300 dark:border-gray-600 shadow-md">
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Right Side: Notes Section (75%) -->
                    <div class="col-span-12 lg:col-span-9 space-y-4">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Notes</h4>
                        
                        @if($this->showForm)
                            <textarea 
                                wire:model.live="methods.{{ $methodKey }}.notes"
                                rows="15"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Enter notes (one per line)..."
                            ></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Each line will be displayed as a bullet point
                            </p>
                        @else
                            @if(!empty($method['notes']))
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                                    <ul class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300">
                                        @foreach(explode("\n", trim($method['notes'])) as $note)
                                            @if(!empty(trim($note)))
                                                <li>{{ trim($note) }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">No notes yet. Click Edit to add notes.</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

