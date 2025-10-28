<x-filament-panels::page>
    <div class="mb-4">
        <a href="{{ url('/admin/how-tos/create') }}" class="text-black underline hover:no-underline text-base font-medium">
            New How To
        </a>
    </div>
    
    <div class="space-y-4" x-data>
        @forelse($this->getHowTos() as $howTo)
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden" x-data="{ open: false }">
                <!-- Header - Clickable -->
                <button 
                    @click="open = !open"
                    class="w-full flex justify-between items-start p-6 hover:bg-gray-50 transition-colors text-left"
                    type="button"
                >
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $howTo->title }}</h3>
                        @if($howTo->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $howTo->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <a 
                            href="{{ url('/admin/how-tos/' . $howTo->id . '/edit') }}" 
                            @click.stop
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors"
                        >
                            Edit
                        </a>
                        <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                
                <!-- Collapsible Content -->
                <div 
                    x-show="open"
                    x-collapse
                    class="border-t border-gray-200"
                >
                    <div class="p-6 pt-4">
                        @if(!empty($howTo->steps) && is_array($howTo->steps))
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Steps to Follow</h4>
                            <div class="space-y-3">
                                @foreach($howTo->steps as $index => $step)
                                    <div class="flex bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <!-- Left Column: Step Number -->
                                        <div class="flex-shrink-0 w-12 flex items-center justify-center">
                                            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white font-bold text-sm">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <!-- Right Column: Step Text -->
                                        <div class="flex-1 ml-4 pl-4 border-l border-gray-300">
                                            <span class="text-base text-gray-900 font-medium leading-relaxed block">{{ $step['step'] ?? '' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">No steps defined yet. Edit to add steps.</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No How To's</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new How To guide.</p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
