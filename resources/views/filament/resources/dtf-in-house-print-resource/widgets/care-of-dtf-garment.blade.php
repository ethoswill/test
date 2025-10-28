<x-filament-widgets::widget>
    <x-filament::section collapsed>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span>Care of DTF Garment</span>
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
                            {{ $this->content ? 'Edit' : 'Add Content' }}
                        </x-filament::button>
                    @endif
                </div>
            </div>
        </x-slot>
        
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            @if($this->showForm)
                <div class="space-y-4">
                    <textarea 
                        wire:model="content" 
                        rows="10" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Enter care instructions here... (Supports HTML formatting)"
                    ></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        💡 Tip: Use HTML tags for formatting: <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;b&gt;bold&lt;/b&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;i&gt;italic&lt;/i&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;u&gt;underline&lt;/u&gt;</code>, <code class="text-xs bg-gray-200 dark:bg-gray-700 px-1 rounded">&lt;a href="url"&gt;link&lt;/a&gt;</code>
                    </p>
                </div>
            @else
                @if(!empty($this->content))
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        {!! $this->content !!}
                    </div>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                        No content yet. Click "Add Content" to get started.
                    </p>
                @endif
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

