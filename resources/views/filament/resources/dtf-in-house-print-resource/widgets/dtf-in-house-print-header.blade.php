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
                        {{ $this->editNotes() }}
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
</x-filament-widgets::widget>

