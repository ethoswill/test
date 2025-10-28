<x-filament-widgets::widget>
    <x-filament::section collapsed>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span>ICC Profiling</span>
                <div class="flex gap-2">
                    @if($this->showForm)
                        <x-filament::button wire:click="saveContent" color="success" size="sm">
                            Save
                        </x-filament::button>
                        <x-filament::button wire:click="toggleEdit" size="sm" color="gray">
                            Cancel
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click="toggleEdit" size="sm" color="primary">
                            {{ $this->content || !empty($this->existingImages) ? 'Edit' : 'Add Content' }}
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </x-slot>
        
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            @if($this->showForm)
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Content Notes
                        </label>
                        <textarea 
                            wire:model="content" 
                            rows="10" 
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Enter your ICC profiling notes here... (Supports HTML formatting)"
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            💡 Tip: Use HTML tags for formatting: <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;b&gt;bold&lt;/b&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;i&gt;italic&lt;/i&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;u&gt;underline&lt;/u&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;a href="url"&gt;link&lt;/a&gt;</code>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Add Image from URL
                        </label>
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                wire:model="imageUrl"
                                placeholder="https://example.com/image.jpg"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            />
                            <x-filament::button wire:click="addImageFromUrl" color="primary" size="sm">
                                Add URL
                            </x-filament::button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Paste an image URL (jpg, png, gif, webp, svg)
                        </p>
                    </div>

                        @if(!empty($this->existingImages))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Current Images
                                </label>
                                <div class="space-y-4">
                                    @foreach($this->existingImages as $index => $image)
                                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                            <img src="{{ $image }}" 
                                                 alt="ICC Profiling Image {{ $index + 1 }}"
                                                 class="w-full h-auto object-contain rounded-lg"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-64 flex items-center justify-center bg-gray-200 dark:bg-gray-800 rounded-lg border-2 border-dashed border-gray-400 hidden">
                                                <div class="text-center">
                                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Image not found</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                </div>
            @else
                @if(!empty($this->content))
                    <div class="prose prose-sm max-w-none dark:prose-invert mb-4">
                        {!! $this->content !!}
                    </div>
                @endif

                @if(!empty($this->existingImages))
                    <div class="space-y-4 mt-4">
                        @foreach($this->existingImages as $index => $image)
                            <div class="w-full">
                                <img src="{{ $image }}" 
                                     alt="ICC Profiling Image {{ $index + 1 }}"
                                     class="w-full h-auto object-contain rounded-lg shadow-md">
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(empty($this->content) && empty($this->existingImages))
                    <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                        No content yet. Click "Add Content" to get started.
                    </p>
                @endif
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

