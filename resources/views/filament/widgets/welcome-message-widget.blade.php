<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-4">
            <div class="text-6xl">
                {{ $this->getWeatherMood() }}
            </div>
            <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $this->getWelcomeMessage() }}
                </h3>
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $this->getFormattedDate() }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $this->getMotivationalTip() }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

