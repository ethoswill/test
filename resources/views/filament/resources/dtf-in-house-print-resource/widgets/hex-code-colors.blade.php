<x-filament-widgets::widget>
    <x-filament::section collapsed>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span>Hex Code Colors</span>
                {{ $this->editContent() }}
            </div>
        </x-slot>
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            @if(!empty($this->content))
                <div class="prose prose-sm max-w-none dark:prose-invert">
                    {!! nl2br(e($this->content)) !!}
                </div>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                    Available hex code colors coming soon...
                </p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

