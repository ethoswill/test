<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2 -ml-6">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Lead Time
            </div>
        </x-slot>
        <div class="prose max-w-none">
            @if($this->content)
                <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $this->content }}
                </div>
            @else
                <p class="text-gray-400 dark:text-gray-500 italic">No lead time information available.</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

