<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2 -ml-6">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Minimums
            </div>
        </x-slot>
        <div class="prose max-w-none">
            @if($this->content)
                <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                    {{ $this->content }}
                </div>
            @else
                <p class="text-gray-400 dark:text-gray-500 italic">No minimums information available.</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

