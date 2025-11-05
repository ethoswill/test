<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Gallery
        </x-slot>
        
        @if(!empty($imageUrls) && count($imageUrls) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                @foreach($imageUrls as $index => $url)
                    @php
                        $url = trim($url);
                    @endphp
                    @if(!empty($url))
                        <div class="relative aspect-square overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                            <img 
                                src="{{ $url }}" 
                                alt="Sock gallery image {{ $index + 1 }}" 
                                class="w-full h-full object-cover" 
                                loading="lazy" 
                            />
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-4">No gallery images added yet.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

