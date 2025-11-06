<x-filament-widgets::widget>
    <div class="widget-content space-y-4">
        <!-- Visual Reference Section -->
        <x-filament::section>
            <x-slot name="heading">
                Visual Reference
            </x-slot>
            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    DTF (Direct To Film) print sample showing detailed graphics and vibrant colors
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 italic">
                    Sample images and production notes for in-house DTF printing
                </p>
            </div>
        </x-filament::section>

        <!-- Team Notes Section -->
        <x-filament::section>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Team Notes</p>
                    @if($this->isEditable)
                        {{ $this->getAction('edit_notes') }}
                    @endif
                </div>
            </x-slot>
            <div class="prose max-w-none dark:prose-invert">
                @if(empty($this->content))
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">No notes available.</p>
                @else
                    {!! $this->content !!}
                @endif
            </div>
        </x-filament::section>
    </div>
    <x-filament-actions::modals />
</x-filament-widgets::widget>

