<x-filament-widgets::widget>
    <div class="widget-content">
        <x-filament::section>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Team Notes</p>
                    @if($this->isEditable)
                        <button 
                            wire:click="openEditModal"
                            class="inline-flex items-center gap-x-2 rounded-lg bg-gray-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"></path>
                            </svg>
                            Edit Notes
                        </button>
                    @endif
                </div>
            </x-slot>
            <div>
                @if(empty($this->content))
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">No notes available.</p>
                @else
                    @php
                        $lines = array_filter(explode("\n", $this->content));
                        $firstLine = !empty($lines) ? trim(array_shift($lines)) : null;
                    @endphp
                    
                    @if($firstLine)
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ $firstLine }}</h3>
                    @endif
                    
                    @if(!empty($lines))
                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-700 dark:text-gray-300">
                            @foreach($lines as $line)
                                <li>{{ trim($line) }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </x-filament::section>
    </div>

    <x-filament::modal wire:model="showEditModal">
        <x-slot name="heading">
            Edit Team Notes
        </x-slot>

        <textarea 
            wire:model="editContent"
            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
            rows="5"
            placeholder="Enter notes for your team..."></textarea>

        <x-slot name="footer">
            <x-filament::button wire:click="closeEditModal">Cancel</x-filament::button>
            <x-filament::button wire:click="saveNotes" color="primary">Save</x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-widgets::widget>

