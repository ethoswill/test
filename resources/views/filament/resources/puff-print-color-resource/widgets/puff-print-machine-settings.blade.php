<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2 -ml-6">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Machine Settings
            </div>
        </x-slot>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Machine Settings Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-700 p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Machine Settings
                    </h3>
                </div>
                <p class="text-xs font-semibold text-blue-700 dark:text-blue-300 mb-4 uppercase tracking-wide">
                    Same for ALL colors
                </p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Temperature</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400">140°C</span>
                    </div>
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Press time</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400">15 Seconds</span>
                    </div>
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Pressure</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400">90 PSI</span>
                    </div>
                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg border border-blue-200 dark:border-blue-700">
                        <span class="text-sm text-gray-600 dark:text-gray-300">Presses</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400">1 Press</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">
                        with plastic transfer sheet
                    </div>
                </div>
            </div>
            
            <!-- Typical Locations Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-700 p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Typical Locations
                    </h3>
                </div>
                <p class="text-xs font-semibold text-green-700 dark:text-green-300 mb-4 uppercase tracking-wide">
                    Placement Areas
                </p>
                <div class="space-y-3">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Left Chest</span>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Center Chest</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">(small and big)</span>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-green-200 dark:border-green-700">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">Back</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Size Grading Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-700 p-6 rounded-xl shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-purple-500 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Size Grading
                    </h3>
                </div>
                <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 mb-4 uppercase tracking-wide">
                    Size Categories
                </p>
                <div class="space-y-3">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Same Size</span>
                        </div>
                        <span class="text-sm text-gray-900 dark:text-white">Small, Medium, Large</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Different Size</span>
                        </div>
                        <span class="text-sm text-gray-900 dark:text-white">XSmall</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Different Size</span>
                        </div>
                        <span class="text-sm text-gray-900 dark:text-white">Kids</span>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">Different Size</span>
                        </div>
                        <span class="text-sm text-gray-900 dark:text-white">XLarge, 2XLarge, 3XL (same size)</span>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

