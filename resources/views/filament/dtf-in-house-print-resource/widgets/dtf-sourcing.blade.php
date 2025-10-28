<x-filament-widgets::widget>
    <x-filament::section collapsed>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span>DTF Sourcing</span>
                <x-filament::button wire:click="openEditModal">
                    Edit
                </x-filament::button>
            </div>
        </x-slot>
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            @if(!empty($this->content))
                <div class="prose prose-sm max-w-none dark:prose-invert">
                    {!! nl2br(e($this->content)) !!}
                </div>
            @else
                <p class="text-sm text-gray-600 dark:text-gray-400 italic">
                    DTF sourcing information coming soon...
                </p>
            @endif
        </div>
    </x-filament::section>

    <x-filament::modal wire:model="showEditModal">
        <form wire:submit="saveContent">
            <x-slot name="heading">
                Edit DTF Sourcing
            </x-slot>
            
            <div class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input type="textarea" wire:model="editContent" rows="10" />
                </x-filament::input.wrapper>
            </div>

            <x-slot name="footer">
                <x-filament::button type="button" wire:click="closeEditModal">
                    Cancel
                </x-filament::button>
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            </x-slot>
        </form>
    </x-filament::modal>
</x-filament-widgets::widget>

